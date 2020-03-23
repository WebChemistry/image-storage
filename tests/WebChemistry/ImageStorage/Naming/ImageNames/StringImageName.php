<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Naming\ImageNames;

use WebChemistry\ImageStorage\Naming\ImageNameInterface;

final class StringImageName implements ImageNameInterface
{

	private string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	public function toString(): string
	{
		return $this->name;
	}

}
