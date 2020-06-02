<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Remover;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

final class EmptyImageRemover implements RemoverInterface
{

	public function supports(PersistentImageInterface $image): bool
	{
		return $image instanceof EmptyImageInterface;
	}

	public function remove(PersistentImageInterface $image): void
	{
		//throw new InvalidArgumentException('Cannot remove empty image');
	}

}
