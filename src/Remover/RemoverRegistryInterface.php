<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Remover;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface RemoverRegistryInterface
{

	public function add(RemoverInterface $remover): void;

	public function remove(PersistentImageInterface $image): void;

}
