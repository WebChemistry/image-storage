<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

use WebChemistry\ImageStorage\Exceptions\ClosedImageException;
use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Scope\Scope;

abstract class Image implements ImageInterface
{

	private string $name;
	private ?Filter $filter = null;
	private Scope $scope;
	private bool $closed = false;

	public function __construct(string $name, ?Scope $scope = null)
	{
		$this->name = ltrim($name, '/');
		$this->scope = $scope ?? new Scope();
	}

	public function getId(): string
	{
		$this->throwIfClosed();

		return $this->scope->toStringWithTrailingSlash() . $this->name;
	}

	public function getName(): string
	{
		$this->throwIfClosed();

		return $this->name;
	}

	public function getScope(): Scope
	{
		$this->throwIfClosed();

		return $this->scope;
	}

	public function getFilter(): ?Filter
	{
		$this->throwIfClosed();

		return $this->filter;
	}

	public function hasFilter(): bool
	{
		$this->throwIfClosed();

		return (bool) $this->filter;
	}

	public function withName(string $name)
	{
		$this->throwIfClosed();

		$clone = clone $this;
		$clone->name = $name;

		return $clone;
	}

	/**
	 * @param mixed[] $options
	 * @return static
	 */
	public function withFilter(string $name, array $options = [])
	{
		$this->throwIfClosed();

		$clone = clone $this;
		$clone->filter = new Filter($name, $options);

		return $clone;
	}

	/**
	 * @return static
	 */
	public function withFilterObject(Filter $filter)
	{
		$this->throwIfClosed();

		$clone = clone $this;
		$clone->filter = $filter;

		return $clone;
	}

	/**
	 * @return static
	 */
	public function getOriginal()
	{
		$clone = clone $this;
		$clone->filter = null;

		return $clone;
	}

	public function isClosed(): bool
	{
		return $this->closed;
	}

	protected function setClosed(): void
	{
		$this->closed = true;
	}

	protected function throwIfClosed(): void
	{
		if ($this->closed) {
			throw new ClosedImageException(
				sprintf('Image %s is closed', $this->scope->toStringWithTrailingSlash() . $this->name)
			);
		}
	}

	final public function __clone()
	{
		$this->throwIfClosed();
	}

}
