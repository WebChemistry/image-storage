<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\NameResolvers;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Resolver\NameResultInterface;
use WebChemistry\ImageStorage\Resolver\NameResolverInterface;
use WebChemistry\ImageStorage\Resolver\NameResults\StringNameResult;

final class OriginalNameResolver implements NameResolverInterface
{

	public function isDynamic(): bool
	{
		return false;
	}

	public function name(ImageInterface $image): NameResultInterface
	{
		return new StringNameResult($image->getName());
	}

}
