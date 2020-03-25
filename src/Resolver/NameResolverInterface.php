<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface NameResolverInterface
{

	public function isDynamic(): bool;

	public function name(ImageInterface $image): NameResultInterface;

}
