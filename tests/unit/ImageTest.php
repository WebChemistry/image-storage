<?php declare(strict_types = 1);

namespace Project\Tests;

use Codeception\Test\Unit;
use WebChemistry\ImageStorage\Entity\Image;
use WebChemistry\ImageStorage\Exceptions\ClosedImageException;
use WebChemistry\ImageStorage\Scope\Scope;

class ImageTest extends Unit
{

	public function testImage(): void
	{
		$image = new class ('foo.jpg', $scope = new Scope('bar')) extends Image {

		};

		$this->assertSame('foo.jpg', $image->getName());
		$this->assertSame('bar/foo.jpg', $image->getId());
		$this->assertSame('jpg', $image->getSuffix());
		$this->assertSame($scope, $image->getScope());
		$this->assertNotSame($image, $new = $image->withFilter('bar'));
		$this->assertSame('bar', $new->getFilter()->getName());
	}

	public function testClose(): void
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
