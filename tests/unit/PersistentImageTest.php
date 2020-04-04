<?php declare(strict_types = 1);

namespace Project\Tests;

use Codeception\Test\Unit;
use WebChemistry\ImageStorage\Entity\PersistentImage;

class PersistentImageTest extends Unit
{

	public function testCreation(): void
	{
		$image = new PersistentImage('name/test.jpg');

		$this->assertSame('test.jpg', $image->getName());
		$this->assertSame('name', $image->getScope()->toString());
	}

}
