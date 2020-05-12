<?php declare(strict_types = 1);

namespace Project\Tests;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\File\FileFactory;
use WebChemistry\ImageStorage\Filesystem\League\LocalLeagueFilesystemFactory;
use WebChemistry\ImageStorage\Filesystem\LocalFilesystem;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactory;
use WebChemistry\ImageStorage\Resolver\FileNameResolvers\OriginalFileNameResolver;
use WebChemistry\ImageStorage\Storage\ImageStorage;
use WebChemistry\ImageStorage\Testing\FileTestCase;
use WebChemistry\ImageStorage\Testing\Filter\FilterProcessor;
use WebChemistry\ImageStorage\Testing\Filter\OperationRegistry;
use WebChemistry\ImageStorage\Testing\Filter\ThumbnailOperation;
use WebChemistry\ImageStorage\Transaction\TransactionHardRemoveFactory;
use WebChemistry\ImageStorage\Transaction\TransactionHardRemoveFactoryInterface;
use WebChemistry\ImageStorage\Uploader\FilePathUploader;

class TransactionHardRemoveTest extends FileTestCase
{

	private TransactionHardRemoveFactoryInterface $transactionFactory;

	private ImageStorageInterface $storage;

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

		$this->storage = $storage = new ImageStorage($fileFactory, new OriginalFileNameResolver(), $processor);

		$this->transactionFactory = new TransactionHardRemoveFactory($storage);
	}

	public function testHardCommit(): void
	{
		$image = $this->storage->persist(new StorableImage(new FilePathUploader($this->imageJpg), 'image.jpg'));
		$this->assertTempFileExists('media/image.jpg');

		$transaction = $this->transactionFactory->create();
		$promised = $transaction->remove($image);

		$this->assertTempFileExists('media/image.jpg');

		$this->assertTrue($promised->isPending());

		$transaction->commitHardRemove();
		$this->assertTempFileNotExists('media/image.jpg');

		$this->assertFalse($promised->isPending());
		$this->assertInstanceOf(EmptyImageInterface::class, $promised->getResult());
	}

}
