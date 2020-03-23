<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface ImageNamerInterface
{

	public function isDynamic(): bool;

	public function name(ImageInterface $image): ImageNameInterface;

}
