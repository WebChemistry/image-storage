<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

final class AbsolutePath implements PathInterface
{

	private string $absolute;
	private PathInterface $path;

	public function __construct(string $absolute, PathInterface $path)
	{
		$this->absolute = rtrim($absolute, '/') . '/';
		$this->path = $path;
	}

	public function toScope(): ?string
	{
		return $this->absolute . $this->path->toScope();
	}

	public function toFilter(): ?string
	{
		return $this->absolute . $this->path->toFilter();
	}

	public function toStringWithoutFilter(): string
	{
		return $this->absolute . $this->path->toStringWithoutFilter();
	}

	public function toString(): string
	{
		return $this->absolute . $this->path->toString();
	}

}
