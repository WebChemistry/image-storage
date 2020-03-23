<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface PathResolverInterface
{

	public function resolve(ImageInterface $image): PathInterface;

}
