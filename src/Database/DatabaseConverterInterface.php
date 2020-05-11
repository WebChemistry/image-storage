<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Database;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface DatabaseConverterInterface
{

	public function convertToDatabase(?ImageInterface $image): ?string;

	public function convertToPhp(?string $value, ?bool $nullable = null): ?PersistentImageInterface;

}
