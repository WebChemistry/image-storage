<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Exceptions\FilterNormalizerNotFoundException;

final class FilterNormalizerCollection implements FilterNormalizerCollectionInterface
{

	/** @var FilterNormalizerInterface[] */
	private array $normalizers = [];

	public function add(FilterNormalizerInterface $normalizer): void
	{
		$this->normalizers[] = $normalizer;
	}

	/**
	 * @inheritDoc
	 */
	public function normalize(ImageInterface $image): ?array
	{
		$filter = $image->getFilter();
		if (!$filter) {
			return null;
		}

		foreach ($this->normalizers as $normalizer) {
			if ($normalizer->supports($filter, $image)) {
				return $normalizer->normalize($filter, $image);
			}
		}

		throw new FilterNormalizerNotFoundException(
			sprintf('Filter normalizer not found for filter %s and image %s', $filter->getName(), $image->getId())
		);
	}

}
