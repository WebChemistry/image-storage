<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming;

use WebChemistry\Image\Entity\ImageInterface;

interface ImageNamerInterface
{

	public function isDynamic(): bool;

	public function name(ImageInterface $image): ImageNameInterface;

}
