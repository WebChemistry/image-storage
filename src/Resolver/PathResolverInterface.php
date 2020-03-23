<?php declare(strict_types = 1);

namespace WebChemistry\Image\Resolver;

use WebChemistry\Image\Entity\ImageInterface;

interface PathResolverInterface
{

	public function resolve(ImageInterface $image): PathInterface;

}
