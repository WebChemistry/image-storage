<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

interface FilterNormalizerInterface
{

	public function supports(FilterInterface $filter): bool;

	/**
	 * @return mixed[]
	 */
	public function normalize(FilterInterface $filter): array;

}
