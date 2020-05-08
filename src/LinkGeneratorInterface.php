<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface LinkGeneratorInterface
{

	/**
	 * @param mixed[] $options
	 */
	public function link(?PersistentImageInterface $image, array $options = []): ?string;

}
