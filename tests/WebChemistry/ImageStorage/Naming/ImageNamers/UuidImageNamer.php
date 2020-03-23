<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\ImageNamers;

use Ramsey\Uuid\Uuid;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Naming\ImageNameInterface;
use WebChemistry\ImageStorage\Naming\ImageNamerInterface;
use WebChemistry\ImageStorage\Naming\ImageNames\UuidImageName;

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
