<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Nette\Utils\Image;
use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\ImagineFilters\Exceptions\OperationNotFoundException;

final class FilterProcessor implements FilterProcessorInterface
{

	private OperationRegistryInterface $operationRegistry;

	public function __construct(OperationRegistryInterface $operationRegistry)
	{
		$this->operationRegistry = $operationRegistry;
	}

	/**
	 * @param mixed[] $options
	 */
	public function process(
		FilterInterface $filter,
		FileInterface $file,
		FileInterface $original,
		array $options = []
	): string
	{
		$operation = $this->operationRegistry->get($filter, $file->getImage()->getScope());

		if (!$operation) {
			throw new OperationNotFoundException(sprintf('Operation not found for %s', $file->getImage()->getId()));
		}

		$operation->operate($image = Image::fromString($original->getContent(), $format), $filter);

		return $image->toString($format);
	}

}
