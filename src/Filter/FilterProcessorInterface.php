<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\File\FileInterface;

interface FilterProcessorInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function process(
		FilterInterface $filter,
		FileInterface $file,
		FileInterface $original,
		array $options = []
	): string;

}
