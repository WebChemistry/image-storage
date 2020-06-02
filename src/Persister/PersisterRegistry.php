<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

final class PersisterRegistry implements PersisterRegistryInterface
{

	/** @var PersisterInterface[] */
	private array $persisters = [];

	public function add(PersisterInterface $persister): void
	{
		$this->persisters[] = $persister;
	}

	public function persist(ImageInterface $image): ImageInterface
	{
		foreach ($this->persisters as $persister) {
			if ($persister->supports($image)) {
				return $persister->persist($image);
			}
		}

		throw new InvalidArgumentException(sprintf('Persist not found for class %s', get_class($image)));
	}

}
