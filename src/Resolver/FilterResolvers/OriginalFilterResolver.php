<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FilterResolvers;

use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Resolver\FilterResolverInterface;

final class OriginalFilterResolver implements FilterResolverInterface
{

	public function resolve(FilterInterface $filter): string
	{
		return '_' . $filter->getName();
	}

}
