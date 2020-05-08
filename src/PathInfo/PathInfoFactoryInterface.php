<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\PathInfo;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface PathInfoFactoryInterface
{

	public function create(ImageInterface $image): PathInfoInterface;

}
