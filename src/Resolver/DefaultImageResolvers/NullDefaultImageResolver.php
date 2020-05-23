<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\DefaultImageResolvers;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\LinkGeneratorInterface;
use WebChemistry\ImageStorage\Resolver\DefaultImageResolverInterface;

final class NullDefaultImageResolver implements DefaultImageResolverInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function resolve(
		LinkGeneratorInterface $linkGenerator,
		?PersistentImageInterface $image,
		array $options = []
	): ?string
	{
		return null;
	}

}
