<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Unit;

use WebChemistry\ImageStorage\Testing\FileTestCase;
use WebChemistry\ImageStorage\Uploader\StringUploader;

class StringUploaderTest extends FileTestCase
{

	public function testUpload(): void
	{
		$uploader = new StringUploader(file_get_contents($this->imageJpg));
		$this->assertSame(file_get_contents($this->imageJpg), $uploader->getContent());
	}

}
