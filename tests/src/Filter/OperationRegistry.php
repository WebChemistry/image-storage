<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Scope\Scope;

final class OperationRegistry implements OperationRegistryInterface
{

	/** @var OperationInterface[] */
	private array $operations = [];

	public function add(OperationInterface $operation): void
	{
		$this->operations[] = $operation;
	}

	public function get(Filter $filter, Scope $scope): ?OperationInterface
	{
		foreach ($this->operations as $operation) {
			if ($operation->supports($filter, $scope)) {
				return $operation;
			}
		}

		return null;
	}

}
