<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming\FilterNamers;

use WebChemistry\Image\Filter\Filter;
use WebChemistry\Image\Naming\FilterNamerInterface;

final class OriginalFilterNamer implements FilterNamerInterface
{

	public function name(Filter $filter): string
	{
		return '_' . $filter->getName();
	}

}
