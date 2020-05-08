<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use LogicException;
use WebChemistry\ImageStorage\File\FileInterface;

final class VoidFilterProcessor implements FilterProcessorInterface
{

	/**
	 * @inheritDoc
	 */
	public function process(FileInterface $target, FileInterface $source, array $options = []): string
	{
		if ($target->getImage()->getFilter()) {
			throw new LogicException(sprintf('%s does not support filters', self::class));
		}

		return $target->getContent();
	}

}
