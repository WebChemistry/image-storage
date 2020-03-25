<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\PathResolvers;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Resolver\BucketResolverInterface;
use WebChemistry\ImageStorage\Resolver\BucketResolvers\BucketResolver;
use WebChemistry\ImageStorage\Resolver\FilterResolverInterface;
use WebChemistry\ImageStorage\Resolver\FilterResolvers\OriginalFilterResolver;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;
use WebChemistry\ImageStorage\Resolver\Path\Path;
use WebChemistry\ImageStorage\Resolver\PathInterface;

final class PathResolver implements PathResolverInterface
{

	private FilterResolverInterface $filterResolver;
	private BucketResolverInterface $bucketResolver;

	public function __construct(?FilterResolverInterface $filterResolver = null, ?BucketResolverInterface $bucketResolver = null)
	{
		$this->filterResolver = $filterResolver ?? new OriginalFilterResolver();
		$this->bucketResolver = $bucketResolver ?? new BucketResolver();
	}

	public function resolve(ImageInterface $image): PathInterface
	{
		$filter = null;
		if (!$image instanceof StorableImageInterface && ($imageFilter = $image->getFilter())) {
			$filter = $this->filterResolver->name($imageFilter);
		}

		return new Path($this->bucketResolver->resolve($image), $image->getScope()->toString(), $filter, $image->getName());
	}

}
