<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FilterResolvers;

use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Resolver\FilterResolverInterface;

final class OriginalFilterResolver implements FilterResolverInterface
{

	public function name(Filter $filter): string
	{
		return '_' . $filter->getName();
	}

}
