<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\ImageNames;

use Ramsey\Uuid\UuidInterface;
use WebChemistry\ImageStorage\Naming\ImageNameInterface;

final class UuidImageName implements ImageNameInterface
{

	private UuidInterface $uuid;

	public function __construct(UuidInterface $uuid)
	{
		$this->uuid = $uuid;
	}

	public function getUuid(): UuidInterface
	{
		return $this->uuid;
	}

	public function toString(): string
	{
		return $this->uuid->toString();
	}

}
