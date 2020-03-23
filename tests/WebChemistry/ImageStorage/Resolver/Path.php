<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

final class Path implements PathInterface
{

	private ?string $scope;
	private ?string $filter;
	private string $name;

	public function __construct(?string $scope, ?string $filter, string $name)
	{
		$this->scope = $scope;
		$this->filter = $filter;
		$this->name = $name;
	}

	public function toScope(): ?string
	{
		return $this->scope;
	}

	public function toFilter(): ?string
	{
		if (!$this->scope && !$this->filter) {
			return null;
		}

		if (!$this->scope || $this->filter) {
			return $this->scope . $this->filter;
		}

		return ($this->scope ? $this->scope . '/' : '') . $this->filter;
	}

	public function toStringWithoutFilter(): string
	{
		if (!$this->scope) {
			return $this->name;
		}

		return $this->scope . '/' . $this->name;
	}

	public function toString(): string
	{
		$path = $this->toFilter();
		if (!$path) {
			return $this->name;
		}

		return $path . '/' . $this->name;
	}

}
