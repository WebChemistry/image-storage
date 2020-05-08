<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\File\FileInterface;

interface FilterProcessorInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function process(FileInterface $target, FileInterface $source, array $options = []): string;

}
