<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Remover;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

interface RemoverInterface
{

	public function supports(PersistentImageInterface $image): bool;

	public function remove(PersistentImageInterface $image): void;

}
