<?php declare(strict_types = 1);

namespace Project\Tests;

use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\File\FileFactory;
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

	private ImageStorage $storage;

	protected function _before(): void
	{
		parent::_before();

		$registry = new OperationRegistry();
		$registry->add(new ThumbnailOperation());

		$processor = new FilterProcessor($registry);
		$fileFactory = new FileFactory(
			new LocalFilesystem($this->getAbsolutePath()),
			new PathInfoFactory()
		);

		$this->storage = $storage = new ImageStorage($fileFactory, new OriginalFileNameResolver(), $processor);

		$this->transactionFactory = new TransactionFactory($storage, $fileFactory);
	}

	public function testPreCommit(): void
	{
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$this->assertTempFileNotExists('media/image.jpg');
	}

	public function testCommit(): void
	{
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$transaction->commit();

		$this->assertTempFileExists('media/image.jpg');
	}

	public function testCommitRemove(): void
	{
		$transaction = $this->transactionFactory->create();
		$image = $this->storage->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));
		$this->assertTempFileExists('media/image.jpg');

		$transaction->remove($image);

		$this->assertTempFileExists('media/image.jpg');

		$transaction->commit();

		$this->assertTempFileNotExists('media/image.jpg');
	}

	public function testRollbackRemove(): void
	{
		$transaction = $this->transactionFactory->create();
		$image = $this->storage->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));
		$this->assertTempFileExists('media/image.jpg');

		$transaction->remove($image);

		$this->assertTempFileExists('media/image.jpg');

		$transaction->commit();

		$this->assertTempFileNotExists('media/image.jpg');

		$transaction->rollback();

		$this->assertTempFileExists('media/image.jpg');
		$this->assertFileEquals($this->imageJpg, $this->getAbsolutePath('media/image.jpg'));
	}

	public function testRollback(): void
	{
		$transaction = $this->transactionFactory->create();
		$transaction->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));

		$transaction->commit();

		$this->assertTempFileExists('media/image.jpg');

		$transaction->rollback();

		$this->assertTempFileNotExists('media/image.jpg');
	}

}
