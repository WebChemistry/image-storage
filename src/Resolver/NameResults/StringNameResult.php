<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\NameResults;

use WebChemistry\ImageStorage\Resolver\NameResultInterface;

final class StringNameResult implements NameResultInterface
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
