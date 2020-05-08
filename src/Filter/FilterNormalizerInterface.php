<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface FilterNormalizerInterface
{

	public function supports(FilterInterface $filter, ImageInterface $image): bool;

	/**
	 * @return mixed[]
	 */
	public function normalize(FilterInterface $filter, ImageInterface $image): array;

}
