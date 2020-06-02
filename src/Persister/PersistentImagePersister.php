<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

final class PersistentImagePersister extends ImagePersisterAbstract
{

	private bool $strict = true;

	public function setStrict(bool $strict): void
	{
		$this->strict = $strict;
	}

	public function supports(ImageInterface $image): bool
	{
		return $image instanceof PersistentImageInterface && !$image instanceof EmptyImageInterface;
	}

	public function persist(ImageInterface $image): ImageInterface
	{
		if (!$image->getFilter()) {
			if ($this->strict) {
				throw new InvalidArgumentException('Cannot persist persistent image with no filter');
			}

			return $image;
		}

		$this->save($image);

		return $image;
	}

}
