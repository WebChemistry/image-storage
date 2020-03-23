<?php namespace Project\Tests;

use WebChemistry\Image\Testing\FileTestCase;
use WebChemistry\Image\Uploader\MoveUploader;

class MoveUploaderTest extends FileTestCase
{

	// tests
	public function testUpload()
	{
		$uploader = new MoveUploader($this->imageJpg);
		$this->assertSame(file_get_contents($this->imageJpg), $uploader->getContent());

		$uploader->save($this->getAbsolutePath(), 'test.jpg');
		$this->assertTempFileExists('test.jpg');
		$this->assertFileNotExists($this->imageJpg);
	}

}
