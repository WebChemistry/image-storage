<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

use WebChemistry\ImageStorage\Exceptions\PromiseException;
use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

final class PromisedImage implements PromisedImageInterface
{

	private ImageInterface $source;

	private ?PersistentImage $result = null;

	/** @var callable[] */
	private array $then = [];

	public function __construct(ImageInterface $source)
	{
		$this->source = $source;
	}

	public function getId(): string
	{
		$this->throwIfPending();

		return $this->result->getId();
	}

	public function getName(): string
	{
		$this->throwIfPending();

		return $this->result->getName();
	}

	public function getSuffix(): ?string
	{
		$this->throwIfPending();

		return $this->result->getSuffix();
	}

	public function getScope(): Scope
	{
		$this->throwIfPending();

		return $this->result->getScope();
	}

	public function getFilter(): ?FilterInterface
	{
		$this->throwIfPending();

		return $this->result->getFilter();
	}

	public function hasFilter(): bool
	{
		$this->throwIfPending();

		return $this->result->hasFilter();
	}

	public function withScope(Scope $scope)
	{
		$this->throwIfPending();

		return $this->result->withScope($scope);
	}

	public function withName(string $name)
	{
		$this->throwIfPending();

		return $this->result->withName($name);
	}

	public function withFilter(string $name, array $options = [])
	{
		$this->throwIfPending();

		return $this->result->withFilter($name, $options);
	}

	public function withFilterObject(FilterInterface $filter)
	{
		$this->throwIfPending();

		return $this->result->withFilterObject($filter);
	}

	public function getOriginal()
	{
		$this->throwIfPending();

		return $this->result->getOriginal();
	}

	public function isClosed(): bool
	{
		$this->throwIfPending();

		return $this->result->isClosed();
	}

	public function close(): void
	{
		$this->throwIfPending();

		$this->result->close();
	}

	// transaction

	public function process(callable $action): void
	{
		if ($this->result) {
			throw new PromiseException('Promised image is already processed');
		}

		$this->result = $action($this->source);

		foreach ($this->then as $callback) {
			$callback($this->result);
		}
	}

	public function then(callable $callable): void
	{
		if ($this->result) {
			throw new PromiseException('Promised image is already processed');
		}

		$this->then[] = $callable;
	}

	public function getResult(): PersistentImageInterface
	{
		$this->throwIfPending();

		return $this->result;
	}

	public function isPending(): bool
	{
		return !$this->result;
	}

	private function throwIfPending(): void
	{
		if ($this->isPending()) {
			throw new PromiseException('Promise is still pending');
		}
	}

}
