<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming;

use WebChemistry\Image\Filter\Filter;

interface FilterNamerInterface
{

	public function name(Filter $filter): string;

}
