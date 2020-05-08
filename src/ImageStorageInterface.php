<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface ImageStorageInterface
{

	public function persist(ImageInterface $image): PersistentImageInterface;

	public function remove(PersistentImageInterface $image): PersistentImageInterface;

}
