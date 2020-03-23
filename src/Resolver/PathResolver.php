<?php declare(strict_types = 1);

namespace WebChemistry\Image\Resolver;

use WebChemistry\Image\Entity\ImageInterface;
use WebChemistry\Image\Naming\FilterNamerInterface;

final class PathResolver implements PathResolverInterface
{

	private ?FilterNamerInterface $filterNamer;

	public function __construct(?FilterNamerInterface $filterNamer = null)
	{
		$this->filterNamer = $filterNamer;
	}

	public function resolve(ImageInterface $image): PathInterface
	{
		$filter = $image->getFilter();
		$filter = $filter ? $this->filterNamer->name($filter) : null;

		return new Path($image->getScope()->toString(), $filter, $image->getName());
	}

}
