<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Remover;

use LogicException;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;

final class RemoverRegistry implements RemoverRegistryInterface
{

	/** @var RemoverInterface[] */
	private array $removers = [];

	public function add(RemoverInterface $remover): void
	{
		$this->removers[] = $remover;
	}

	public function remove(PersistentImageInterface $image): void
	{
		foreach ($this->removers as $remover) {
			if ($remover->supports($image)) {
				$remover->remove($image);

				return;
			}
		}

		throw new LogicException(sprintf('Remover for class %s not found', get_class($image)));
	}

}
