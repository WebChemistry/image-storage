<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\LinkGeneratorInterface;

interface DefaultImageResolverInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function resolve(
		LinkGeneratorInterface $linkGenerator,
		?PersistentImageInterface $image,
		array $options = []
	): ?string;

}
