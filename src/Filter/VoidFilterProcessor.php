<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use LogicException;
use WebChemistry\ImageStorage\File\FileInterface;

final class VoidFilterProcessor implements FilterProcessorInterface
{

	/**
	 * @inheritDoc
	 */
	public function process(FileInterface $file, FileInterface $original, array $options = []): string
	{
		if ($file->getImage()->getFilter()) {
			throw new LogicException(sprintf('%s does not support filters', self::class));
		}

		return $file->getContent();
	}

}
