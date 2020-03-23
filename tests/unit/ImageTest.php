<?php namespace Project\Tests;

use WebChemistry\Image\Entity\Image;
use WebChemistry\Image\Exceptions\ClosedImageException;
use WebChemistry\Image\Scope\Scope;

class ImageTest extends \Codeception\Test\Unit
{

	public function testImage()
	{
		$image = new class ('foo.jpg', $scope = new Scope('bar')) extends Image {};

		$this->assertSame('foo.jpg', $image->getName());
		$this->assertSame('bar/foo.jpg', $image->getId());
		$this->assertSame($scope, $image->getScope());
		$this->assertNotSame($image, $new = $image->withFilter('bar'));
		$this->assertSame('bar', $new->getFilter()->getName());
	}

	public function testClose()
	{
		$image = new class('foo.jpg') extends Image {

			public function close(): void
			{
				$this->setClosed();
			}

		};

		$this->assertFalse($image->isClosed());
		$image->close();

		$this->assertTrue($image->isClosed());

		$this->expectException(ClosedImageException::class);
		$image->getId();
	}

}
