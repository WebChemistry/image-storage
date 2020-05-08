<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

interface FilterInterface
{

	public function getName(): string;

	/**
	 * @return mixed[]
	 */
	public function getOptions(): array;

}
