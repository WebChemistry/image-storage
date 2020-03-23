<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;

class ImageType extends StringType
{

	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if ($value instanceof ImageInterface) {
			return $value->getId();
		} elseif ($value === null) {
			return $value;
		}

		throw ConversionException::conversionFailedInvalidType(
			$value,
			$this->getName(),
			['null', ImageInterface::class]
		);
	}

	public function convertToPHPValue($value, AbstractPlatform $platform)
	{
		if ($value === null) {
			return $value;
		}

		return new PersistentImage((string) $value);
	}

	public function getName()
	{
		return 'image';
	}

}
