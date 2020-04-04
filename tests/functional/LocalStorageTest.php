<?php declare(strict_types = 1);

namespace Project\Tests;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use WebChemistry\ImageStorage\Adapter\SimpleAdapter;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\ImagineFilters\FilterLoader;
use WebChemistry\ImageStorage\ImagineFilters\FilterProcessor;
use WebChemistry\ImageStorage\Scope\Scope;
use WebChemistry\ImageStorage\Storages\LocalStorage;
use WebChemistry\ImageStorage\Testing\FileTestCase;
use WebChemistry\ImageStorage\Testing\Filter\ThumbnailFilter;
use WebChemistry\ImageStorage\Uploader\LocalUploader;

class LocalStorageTest extends FileTestCase
{

	private LocalStorage $storage;

	protected function _before(): void
	{
		parent::_before();

		$loader = new FilterLoader();
		$loader->addFilter(new ThumbnailFilter());
		$processor = new FilterProcessor($loader);

		$local = new Local($this->getAbsolutePath());
		$filesystem = new Filesystem($local);
		$adapter = new SimpleAdapter('http://localhost', $filesystem, null, null, $processor);

		$this->storage = new LocalStorage($adapter);
	}

	public function testPersist(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('name.jpg', $persistent->getId());
	}

	public function testPersistScope(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/namespace/scope/name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('namespace/scope/name.jpg', $persistent->getId());
	}

	public function testRemove(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('media/name.jpg');
	}

	public function testRemoveScope(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/namespace/scope/name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('media/name.jpg');
	}

	public function testUrl(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertSame('http://localhost/media/name.jpg', $this->storage->toUrl($persistent));
	}

	public function testFiltersWithNewUpload(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');
		$image = $image->withFilter('thumbnail');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('media/name.jpg');
		$size = getimagesize($this->getAbsolutePath('media/name.jpg'));
		$this->assertSame(15, $size[0]);
		$this->assertSame(15, $size[1]);
	}

	public function testFilterExistingImage(): void
	{
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');
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
		$image = new StorableImage(new LocalUploader($this->imageJpg), 'name.jpg');
		$persistent = $this->storage->persist($image);

		$link = $this->storage->toUrl($persistent->withFilter('thumbnail'));

		$this->assertSame('http://localhost/cache/_thumbnail/name.jpg', $link);
		$this->assertTempFileExists('media/name.jpg');
		$this->assertTempFileExists('cache/_thumbnail/name.jpg');
		$size = getimagesize($this->getAbsolutePath('cache/_thumbnail/name.jpg'));
		$this->assertSame(15, $size[0]);
		$this->assertSame(15, $size[1]);
	}

	public function testToUrlWithFilterAndImageNotExists(): void
	{
		$persistent = new PersistentImage('image.jpg');

		$link = $this->storage->toUrl($persistent->withFilter('thumbnail'));

		$this->assertNull($link);
	}

	public function testToUrlAndImageNotExists(): void
	{
		$persistent = new PersistentImage('image.jpg');

		$link = $this->storage->toUrl($persistent);

		$this->assertNull($link);
	}

}
