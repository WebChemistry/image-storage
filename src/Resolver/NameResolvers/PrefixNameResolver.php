<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\NameResolvers;

use Nette\Utils\Random;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Resolver\NameResultInterface;
use WebChemistry\ImageStorage\Resolver\NameResolverInterface;
use WebChemistry\ImageStorage\Resolver\NameResults\StringNameResult;

final class PrefixNameResolver implements NameResolverInterface
{

	public function isDynamic(): bool
	{
		return true;
	}

	public function name(ImageInterface $image): NameResultInterface
	{
		return new StringNameResult(Random::generate() . '__' . $image->getName());
	}

}
