<?php declare(strict_types = 1);

namespace Project\Tests;

use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\File\FileFactory;
use WebChemistry\ImageStorage\Filesystem\LocalFilesystem;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactory;
use WebChemistry\ImageStorage\Persister\EmptyImagePersister;
use WebChemistry\ImageStorage\Persister\PersistentImagePersister;
use WebChemistry\ImageStorage\Persister\PersisterRegistry;
use WebChemistry\ImageStorage\Persister\StorableImagePersister;
use WebChemistry\ImageStorage\Remover\EmptyImageRemover;
use WebChemistry\ImageStorage\Remover\PersistentImageRemover;
use WebChemistry\ImageStorage\Remover\RemoverRegistry;
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
			$filesystem = new LocalFilesystem($this->getAbsolutePath()),
			$pathInfoFactory = new PathInfoFactory()
		);

		$persisterRegistry = new PersisterRegistry();
		$persisterRegistry->add(new EmptyImagePersister());
		$persisterRegistry->add(new PersistentImagePersister($fileFactory, $processor));
		$persisterRegistry->add(new StorableImagePersister($fileFactory, $processor, new OriginalFileNameResolver()));

		$removerRegistry = new RemoverRegistry();
		$removerRegistry->add(new EmptyImageRemover());
		$removerRegistry->add(new PersistentImageRemover($fileFactory, $pathInfoFactory, $filesystem));

		$this->storage = $storage = new ImageStorage($persisterRegistry, $removerRegistry);

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
