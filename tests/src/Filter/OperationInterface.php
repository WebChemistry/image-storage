<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Nette\Utils\Image;
use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Scope\Scope;

interface OperationInterface
{

	public function supports(Filter $filter, Scope $scope): bool;

	public function operate(Image $image, Filter $filter): void;

}
