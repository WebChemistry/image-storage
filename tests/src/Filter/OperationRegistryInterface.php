<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

interface OperationRegistryInterface
{

	public function add(OperationInterface $operation): void;

	public function get(FilterInterface $filter, Scope $scope): ?OperationInterface;

}
