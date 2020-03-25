<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\Path;

use WebChemistry\ImageStorage\Resolver\PathInterface;

final class Path implements PathInterface
{

	private ?string $scope;
	private ?string $filter;
	private string $name;
	private string $bucket;
	private string $delimiter;

	public function __construct(string $bucket, ?string $scope, ?string $filter, string $name, string $delimiter = '/')
	{
		$this->scope = $scope;
		$this->filter = $filter;
		$this->name = $name;
		$this->bucket = $bucket;
		$this->delimiter = $delimiter;
	}

	public function toBucket(): string
	{
		return $this->bucket;
	}

	public function toScope(): string
	{
		return $this->scope;
	}

	public function toFilter(): string
	{
		if (!$this->scope && !$this->filter) {
			return $this->bucket;
		}

		if (!$this->scope || $this->filter) {
			return $this->bucket . $this->delimiter . $this->scope . $this->filter;
		}

		return $this->bucket . $this->delimiter . ($this->scope ? $this->scope . $this->delimiter : '') . $this->filter;
	}

	public function toStringWithoutFilter(): string
	{
		if (!$this->scope) {
			return $this->bucket . $this->delimiter . $this->name;
		}

		return $this->bucket . $this->delimiter . $this->scope . $this->delimiter . $this->name;
	}

	public function toString(): string
	{
		$path = $this->toFilter();
		if (!$path) {
			return $this->bucket . $this->delimiter . $this->name;
		}

		return $path . $this->delimiter . $this->name;
	}

}
