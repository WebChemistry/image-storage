<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming\ImageNamers;

use WebChemistry\Image\Entity\ImageInterface;
use WebChemistry\Image\Naming\ImageNameInterface;
use WebChemistry\Image\Naming\ImageNamerInterface;
use WebChemistry\Image\Naming\ImageNames\StringImageName;

final class OriginalImageNamer implements ImageNamerInterface
{

	public function isDynamic(): bool
	{
		return false;
	}

	public function name(ImageInterface $image): ImageNameInterface
	{
		return new StringImageName($image->getName());
	}

}
