<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming\ImageNamers;

use Nette\Utils\Random;
use WebChemistry\Image\Entity\ImageInterface;
use WebChemistry\Image\Naming\ImageNameInterface;
use WebChemistry\Image\Naming\ImageNamerInterface;
use WebChemistry\Image\Naming\ImageNames\StringImageName;

final class PrefixImageNamer implements ImageNamerInterface
{

	public function isDynamic(): bool
	{
		return true;
	}

	public function name(ImageInterface $image): ImageNameInterface
	{
		return new StringImageName(Random::generate() . '__' . $image->getName());
	}

}
