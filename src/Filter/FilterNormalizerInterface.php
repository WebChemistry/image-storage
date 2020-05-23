<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface FilterNormalizerInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function supports(FilterInterface $filter, ImageInterface $image, array $options): bool;

	/**
	 * @param mixed[] $options
	 * @return mixed[]
	 */
	public function normalize(FilterInterface $filter, ImageInterface $image, array $options): array;

}
