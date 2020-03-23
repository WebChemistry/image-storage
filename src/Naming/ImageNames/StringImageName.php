<?php declare(strict_types = 1);

namespace WebChemistry\Image\Naming\ImageNames;

use WebChemistry\Image\Naming\ImageNameInterface;

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
