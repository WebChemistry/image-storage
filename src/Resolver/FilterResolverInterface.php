<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Filter\Filter;

interface FilterResolverInterface
{

	public function name(Filter $filter): string;

}
