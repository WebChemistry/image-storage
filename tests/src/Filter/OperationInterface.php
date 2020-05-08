<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Nette\Utils\Image;
use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

interface OperationInterface
{

	public function supports(FilterInterface $filter, Scope $scope): bool;

	public function operate(Image $image, FilterInterface $filter): void;

}
