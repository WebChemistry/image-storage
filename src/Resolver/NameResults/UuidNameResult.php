<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\NameResults;

use Ramsey\Uuid\UuidInterface;
use WebChemistry\ImageStorage\Resolver\NameResultInterface;

final class UuidNameResult implements NameResultInterface
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
