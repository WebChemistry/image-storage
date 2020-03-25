<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\BucketResolvers;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Resolver\BucketResolverInterface;

final class BucketResolver implements BucketResolverInterface
{

	public function resolve(ImageInterface $image): string
	{
		if ($image instanceof StorableImageInterface || !$image->getFilter()) {
			return 'media';
		}

		return 'cache';
	}

}
