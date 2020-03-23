<?php namespace Project\Tests;

use WebChemistry\Image\Entity\PersistentImage;

class PersistentImageTest extends \Codeception\Test\Unit
{

	// tests
	public function testCreation()
	{
		$image = new PersistentImage('name/test.jpg');

		$this->assertSame('test.jpg', $image->getName());
		$this->assertSame('name', $image->getScope()->toString());
	}

}
