<?php declare(strict_types = 1);

namespace Project\Tests;

use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\File\FileFactory;
use WebChemistry\ImageStorage\Filesystem\League\LocalLeagueFilesystemFactory;
use WebChemistry\ImageStorage\Filesystem\LocalFilesystem;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactory;
use WebChemistry\ImageStorage\Resolver\FileNameResolvers\OriginalFileNameResolver;
use WebChemistry\ImageStorage\Storage\ImageStorage;
use WebChemistry\ImageStorage\Testing\FileTestCase;
use WebChemistry\ImageStorage\Testing\Filter\FilterProcessor;
use WebChemistry\ImageStorage\Testing\Filter\OperationRegistry;
use WebChemistry\ImageStorage\Testing\Filter\ThumbnailOperation;
use WebChemistry\ImageStorage\Transaction\TransactionFactory;
use WebChemistry\ImageStorage\Transaction\TransactionFactoryInterface;
use WebChemistry\ImageStorage\Uploader\FilePathUploader;

class TransactionTest extends FileTestCase
{

	private TransactionFactoryInterface $transactionFactory;

	protected function _before(): void
	{
		parent::_before();

		$registry = new OperationRegistry();
		$registry->add(new ThumbnailOperation());

		$processor = new FilterProcessor($registry);
		$fileFactory = new FileFactory(
			new LocalFilesystem(new LocalLeagueFilesystemFactory($this->getAbsolutePath())),
			new PathInfoFactory()
		);

		$storage = new ImageStorage($fileFactory, new OriginalFileNameResolver(), $processor);

		$this->transactionFactory = new TransactionFactory($storage);
	}

	public function testPreCommit() {
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$this->assertTempFileNotExists('media/image.jpg');
	}

	public function testCommit() {
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$transaction->commit();

		$this->assertTempFileExists('media/image.jpg');
	}

	public function testRollback() {
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$transaction->commit();

		$this->assertTempFileExists('media/image.jpg');

		$transaction->rollback();

		$this->assertTempFileNotExists('media/image.jpg');
	}

}
