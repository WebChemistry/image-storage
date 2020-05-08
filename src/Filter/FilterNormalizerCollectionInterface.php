<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface FilterNormalizerCollectionInterface
{

	/**
	 * @return mixed[]|null
	 */
	public function normalize(ImageInterface $image): ?array;

}
