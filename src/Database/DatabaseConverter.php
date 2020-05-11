<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Database;

use WebChemistry\ImageStorage\Entity\EmptyImage;
use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

final class DatabaseConverter implements DatabaseConverterInterface
{

	private bool $nullable = true;

	public function convertToDatabase(?ImageInterface $image): ?string
	{
		if (!$image) {
			return null;
		}

		if ($image instanceof StorableImageInterface) {
			throw new InvalidArgumentException(
				sprintf('Cannot convert %s to database, first persist image and pass the result', $image->getId())
			);
		}

		if ($image instanceof PromisedImageInterface) {
			if ($image->isPending()) {
				throw new InvalidArgumentException(sprintf('Given image is still pending'));
			}

			$image = $image->getResult();
		}

		if ($image instanceof EmptyImageInterface) {
			return null;
		}

		return $image->getId();
	}

	public function convertToPhp(?string $value, ?bool $nullable = null): ?PersistentImageInterface
	{
		if (!$value) {
			if ($nullable === null) {
				$nullable = $this->nullable;
			}

			return $nullable ? null : new EmptyImage();
		}

		return new PersistentImage($value);
	}

}
