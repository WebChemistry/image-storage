<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Testing\Filter;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use WebChemistry\ImageStorage\Filter\Filter;
use WebChemistry\ImageStorage\ImagineFilters\ImageFilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

final class ThumbnailFilter implements ImageFilterInterface
{

	public function supports(Filter $filter, Scope $scope): bool
	{
		return $filter->getName() === 'thumbnail';
	}

	public function filter(ImageInterface $image, Filter $filter): void
	{
		$image->resize(new Box(15, 15));
	}

}
