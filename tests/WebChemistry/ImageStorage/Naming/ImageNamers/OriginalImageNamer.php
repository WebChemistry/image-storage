<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\ImageNamers;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Naming\ImageNameInterface;
use WebChemistry\ImageStorage\Naming\ImageNamerInterface;
use WebChemistry\ImageStorage\Naming\ImageNames\StringImageName;

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
