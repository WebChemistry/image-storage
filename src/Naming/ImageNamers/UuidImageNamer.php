<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming\ImageNamers;

use Ramsey\Uuid\Uuid;
use WebChemistry\Image\Entity\ImageInterface;
use WebChemistry\Image\Naming\ImageNameInterface;
use WebChemistry\Image\Naming\ImageNamerInterface;
use WebChemistry\Image\Naming\ImageNames\UuidImageName;

final class UuidImageNamer implements ImageNamerInterface
{

	public function isDynamic(): bool
	{
		return true;
	}

	public function name(ImageInterface $image): ImageNameInterface
	{
		return new UuidImageName(Uuid::uuid4());
	}

}
