<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\DefaultImageResolvers;

use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\LinkGeneratorInterface;
use WebChemistry\ImageStorage\Resolver\DefaultImageResolverInterface;
use WebChemistry\ImageStorage\Utility\RecursionGuard;

final class ScopeDefaultImageResolver implements DefaultImageResolverInterface
{

	use RecursionGuard;

	/** @var string[] */
	private array $lookup;

	/**
	 * @param string[] $lookup
	 */
	public function __construct(array $lookup)
	{
		$this->lookup = $lookup;
	}

	/**
	 * @inheritDoc
	 */
	public function resolve(
		LinkGeneratorInterface $linkGenerator,
		?PersistentImageInterface $image,
		array $options = []
	): ?string
	{
		if ($this->isRecursion($options)) {
			return null;
		}

		$default = $options['scope'] ?? $this->getScopeFromImage($image);

		if (!$default) {
			return null;
		}

		if (!isset($this->lookup[$default])) {
			return null;
		}

		return $linkGenerator->link(
			new PersistentImage($this->lookup[$default]),
			$this->setRecursion($options)
		);
	}

	private function getScopeFromImage(?PersistentImageInterface $image): ?string
	{
		if (!$image) {
			return null;
		}

		return $image->getScope()->toNullableString();
	}

}
