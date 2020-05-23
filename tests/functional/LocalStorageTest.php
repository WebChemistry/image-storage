<?php declare(strict_types = 1);

namespace Project\Tests;

use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\File\FileFactory;
use WebChemistry\ImageStorage\Filesystem\LocalFilesystem;
use WebChemistry\ImageStorage\LinkGenerator\LinkGenerator;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactory;
use WebChemistry\ImageStorage\Resolver\DefaultImageResolvers\NullDefaultImageResolver;
use WebChemistry\ImageStorage\Resolver\FileNameResolvers\OriginalFileNameResolver;
use WebChemistry\ImageStorage\Scope\Scope;
use WebChemistry\ImageStorage\Storage\ImageStorage;
use WebChemistry\ImageStorage\Testing\FileTestCase;
use WebChemistry\ImageStorage\Testing\Filter\FilterProcessor;
use WebChemistry\ImageStorage\Testing\Filter\OperationRegistry;
use WebChemistry\ImageStorage\Testing\Filter\ThumbnailOperation;
use WebChemistry\ImageStorage\Uploader\FilePathUploader;

class LocalStorageTest extends FileTestCase
{

	private ImageStorage $storage;

	private LinkGenerator $linkGenerator;

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
		$defaultImageResolver = new NullDefaultImageResolver();

		$this->storage = new ImageStorage($fileFactory, new OriginalFileNameResolver(), $processor);
		$this->linkGenerator = new LinkGenerator($this->storage, $fileFactory, $defaultImageResolver);
	}

	public function testPersist(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('name.jpg', $persistent->getId());
	}

	public function testPersistScope(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/namespace/scope/name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('namespace/scope/name.jpg', $persistent->getId());
	}

	public function testRemove(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('media/name.jpg');
	}

	public function testRemoveScope(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/namespace/scope/name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('media/name.jpg');
	}

	public function testUrl(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertSame('/media/name.jpg', $this->linkGenerator->link($persistent));
	}

	public function testFiltersWithNewUpload(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');
		$image = $image->withFilter('thumbnail');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');
		$size = getimagesize($this->getAbsolutePath('media/name.jpg'));
		$this->assertSame(15, $size[0]);
		$this->assertSame(15, $size[1]);
	}

	public function testFilterExistingImage(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');
		$persistent = $this->storage->persist($image);

		$this->storage->persist($persistent->withFilter('thumbnail'));

		$this->assertTempFileExists('media/name.jpg');
		$this->assertTempFileExists('cache/_thumbnail/name.jpg');
		$size = getimagesize($this->getAbsolutePath('cache/_thumbnail/name.jpg'));
		$this->assertSame(15, $size[0]);
		$this->assertSame(15, $size[1]);
	}

	public function testToUrlWithFilter(): void
	{
		$image = new StorableImage(new FilePathUploader($this->imageJpg), 'name.jpg');
		$persistent = $this->storage->persist($image);

		$link = $this->linkGenerator->link($persistent->withFilter('thumbnail'));

		$this->assertSame('/cache/_thumbnail/name.jpg', $link);
		$this->assertTempFileExists('media/name.jpg');
		$this->assertTempFileExists('cache/_thumbnail/name.jpg');
		$size = getimagesize($this->getAbsolutePath('cache/_thumbnail/name.jpg'));
		$this->assertSame(15, $size[0]);
		$this->assertSame(15, $size[1]);
	}

	public function testToUrlWithFilterAndImageNotExists(): void
	{
		$persistent = new PersistentImage('image.jpg');

		$link = $this->linkGenerator->link($persistent->withFilter('thumbnail'));

		$this->assertNull($link);
	}

	public function testToUrlAndImageNotExists(): void
	{
		$persistent = new PersistentImage('image.jpg');

		$link = $this->linkGenerator->link($persistent);

		$this->assertNull($link);
	}

}
