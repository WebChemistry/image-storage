<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Filter\FilterInterface;

interface FilterResolverInterface
{

	public function resolve(FilterInterface $filter): string;

}
