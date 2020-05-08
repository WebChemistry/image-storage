<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Nette\Utils\Image;
use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

final class ThumbnailOperation implements OperationInterface
{

	public function supports(FilterInterface $filter, Scope $scope): bool
	{
		return $filter->getName() === 'thumbnail';
	}

	public function operate(Image $image, FilterInterface $filter): void
	{
		$image->resize(15, 15, $image::EXACT);
	}

}
