<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use LogicException;
use Nette\Utils\Image;
use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;

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
	public function process(FileInterface $file, FileInterface $original, array $options = []): string
	{
		$filter = $file->getImage()->getFilter();
		if (!$filter) {
			throw new LogicException('Filter not found');
		}

		$operation = $this->operationRegistry->get($filter, $file->getImage()->getScope());

		if (!$operation) {
			throw new LogicException(sprintf('Operation not found for %s', $file->getImage()->getId()));
		}

		$operation->operate($image = Image::fromString($original->getContent(), $format), $filter);

		return $image->toString($format);
	}

}
