<?php declare(strict_types = 1);

namespace WebChemistry\Image;

use WebChemistry\Image\Entity\ImageInterface;
use WebChemistry\Image\Entity\PersistentImageInterface;
use WebChemistry\Image\Entity\StorableImageInterface;

interface ImageStorageInterface
{

	public function persist(StorableImageInterface $image): PersistentImageInterface;

	public function remove(PersistentImageInterface $image): void;

	public function toUrl(?PersistentImageInterface $image, array $options = []): ?string;

}
