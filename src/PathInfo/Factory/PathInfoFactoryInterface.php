<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\PathInfo\Factory;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfoInterface;

interface PathInfoFactoryInterface
{

	public function create(ImageInterface $image): PathInfoInterface;

}
