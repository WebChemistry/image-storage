<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\PathInfo\Factory;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfo;
use WebChemistry\ImageStorage\PathInfo\PathInfoInterface;
use WebChemistry\ImageStorage\Resolver\BucketResolverInterface;
use WebChemistry\ImageStorage\Resolver\BucketResolvers\BucketResolver;
use WebChemistry\ImageStorage\Resolver\FilterResolverInterface;
use WebChemistry\ImageStorage\Resolver\FilterResolvers\OriginalFilterResolver;

final class PathInfoFactory implements PathInfoFactoryInterface
{

	private FilterResolverInterface $filterResolver;

	private BucketResolverInterface $bucketResolver;

	public function __construct(?FilterResolverInterface $filterResolver = null, ?BucketResolverInterface $bucketResolver = null)
	{
		$this->filterResolver = $filterResolver ?? new OriginalFilterResolver();
		$this->bucketResolver = $bucketResolver ?? new BucketResolver();
	}

	public function create(ImageInterface $image): PathInfoInterface
	{
		$filter = null;
		if (!$image instanceof StorableImageInterface && ($imageFilter = $image->getFilter())) {
			$filter = $this->filterResolver->resolve($imageFilter);
		}

		return new PathInfo($this->bucketResolver->resolve($image), $image->getScope()->toString(), $filter, $image->getName());
	}

}
