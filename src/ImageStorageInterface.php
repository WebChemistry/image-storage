<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface ImageStorageInterface extends UnitOfWork
{

	public function toUrl(?PersistentImageInterface $image, array $options = []): ?string;

}
