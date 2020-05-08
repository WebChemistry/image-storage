<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Nette\Utils\Image;
use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\Scope\Scope;

final class ThumbnailOperation implements OperationInterface
{

	public function supports(Filter $filter, Scope $scope): bool
	{
		return $filter->getName() === 'thumbnail';
	}

	public function operate(Image $image, Filter $filter): void
	{
		$image->resize(15, 15, $image::EXACT);
	}

}
