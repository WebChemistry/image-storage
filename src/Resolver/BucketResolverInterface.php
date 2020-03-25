<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface BucketResolverInterface
{

	public function resolve(ImageInterface $image): string;

}
