<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;

final class FilterNormalizerCollection implements FilterNormalizerCollectionInterface
{

	/** @var FilterNormalizerInterface[] */
	private array $normalizers;

	/**
	 * @param FilterNormalizerInterface[] $normalizers
	 */
	public function __construct(array $normalizers = [])
	{
		$this->normalizers = $normalizers;
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
			if ($normalizer->supports($filter)) {
				return $normalizer->normalize($filter);
			}
		}

		return null;
	}

}
