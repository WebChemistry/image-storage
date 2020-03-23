<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\ImageNamers;

use Nette\Utils\Random;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Naming\ImageNameInterface;
use WebChemistry\ImageStorage\Naming\ImageNamerInterface;
use WebChemistry\ImageStorage\Naming\ImageNames\StringImageName;

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
