<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\FilterNamers;

use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Naming\FilterNamerInterface;

final class OriginalFilterNamer implements FilterNamerInterface
{

	public function name(Filter $filter): string
	{
		return '_' . $filter->getName();
	}

}
