<?php namespace Project\Tests;

use WebChemistry\Image\Entity\PersistentImageInterface;
use WebChemistry\Image\Entity\StorableImage;
use WebChemistry\Image\Scope\Scope;
use WebChemistry\Image\Storages\SimpleLocalStorage;
use WebChemistry\Image\Testing\FileTestCase;
use WebChemistry\Image\Uploader\CopyUploader;

class SimpleLocalStorageTest extends FileTestCase
{

	private SimpleLocalStorage $storage;

	protected function _before()
	{
		parent::_before();

		$this->storage = new SimpleLocalStorage($this->getAbsolutePath(), 'http://localhost/');
	}

	public function testPersist()
	{
		$image = new StorableImage(new CopyUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('name.jpg', $persistent->getId());
	}

	public function testPersistScope()
	{
		$image = new StorableImage(new CopyUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('namespace/scope/name.jpg');
		$this->assertInstanceOf(PersistentImageInterface::class, $persistent);
		$this->assertSame('namespace/scope/name.jpg', $persistent->getId());
	}

	public function testRemove()
	{
		$image = new StorableImage(new CopyUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('name.jpg');
	}

	public function testRemoveScope()
	{
		$image = new StorableImage(new CopyUploader($this->imageJpg), 'name.jpg', new Scope('namespace', 'scope'));

		$persistent = $this->storage->persist($image);

		$this->assertTempFileExists('namespace/scope/name.jpg');

		$this->storage->remove($persistent);
		$this->assertTempFileNotExists('name.jpg');
	}

	public function testUrl()
	{
		$image = new StorableImage(new CopyUploader($this->imageJpg), 'name.jpg');

		$persistent = $this->storage->persist($image);

		$this->assertSame('http://localhost/name.jpg', $this->storage->toUrl($persistent));
	}

}
