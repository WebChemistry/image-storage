<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;

interface ImageStorageInterface
{

	public function persist(StorableImageInterface $image): PersistentImageInterface;

	public function remove(PersistentImageInterface $image): void;

	public function toUrl(?PersistentImageInterface $image, array $options = []): ?string;

}
