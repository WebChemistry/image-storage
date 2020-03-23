<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming;

use WebChemistry\ImageStorage\Filter\Filter;

interface FilterNamerInterface
{

	public function name(Filter $filter): string;

}
