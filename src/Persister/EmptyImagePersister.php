<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\EmptyImage;
use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

final class EmptyImagePersister implements PersisterInterface
{

	private bool $strict = true;

	public function setStrict(bool $strict): void
	{
		$this->strict = $strict;
	}

	public function supports(ImageInterface $image): bool
	{
		return $image instanceof EmptyImageInterface;
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		if ($this->strict) {
			throw new InvalidArgumentException('Cannot persist empty image');
		}

		return new EmptyImage();
	}

}
