<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\NameResolvers;

use Ramsey\Uuid\Uuid;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Resolver\NameResultInterface;
use WebChemistry\ImageStorage\Resolver\NameResolverInterface;
use WebChemistry\ImageStorage\Resolver\NameResults\UuidNameResult;

final class UuidNameResolver implements NameResolverInterface
{

	public function isDynamic(): bool
	{
		return true;
	}

	public function name(ImageInterface $image): NameResultInterface
	{
		return new UuidNameResult(Uuid::uuid4());
	}

}
