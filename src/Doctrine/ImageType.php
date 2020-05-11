<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use WebChemistry\ImageStorage\Database\DatabaseConverter;
use WebChemistry\ImageStorage\Database\DatabaseConverterInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;

class ImageType extends StringType
{

	private DatabaseConverterInterface $databaseConverter;

	public function getDatabaseConverter(): DatabaseConverterInterface
	{
		if (!isset($this->databaseConverter)) {
			$this->databaseConverter = new DatabaseConverter();
		}

		return $this->databaseConverter;
	}

	/**
	 * @inheritDoc
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform)
	{
		if (!$value instanceof ImageInterface || $value !== null) {
			throw ConversionException::conversionFailedInvalidType(
				$value,
				$this->getName(),
				['null', ImageInterface::class]
			);
		}

		return $this->getDatabaseConverter()->convertToDatabase($value);
	}

	/**
	 * @inheritDoc
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform) // phpcs:ignore Generic.NamingConventions.CamelCapsFunctionName.ScopeNotCamelCaps
	{
		if (!is_string($value) || $value !== null) {
			throw ConversionException::conversionFailedInvalidType(
				$value,
				$this->getName(),
				['null', 'string']
			);
		}

		return $this->databaseConverter->convertToPhp($value);
	}

	/**
	 * @inheritDoc
	 */
	public function getName()
	{
		return 'image';
	}

}
