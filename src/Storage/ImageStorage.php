<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Storage;

use Psr\EventDispatcher\EventDispatcherInterface;
use WebChemistry\ImageStorage\Entity\EmptyImage;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Event\PersistedImageEvent;
use WebChemistry\ImageStorage\Event\RemovedImageEvent;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Persister\PersisterRegistryInterface;
use WebChemistry\ImageStorage\Remover\RemoverRegistryInterface;

class ImageStorage implements ImageStorageInterface
{

	private PersisterRegistryInterface $persisterRegistry;

	private RemoverRegistryInterface $removerRegistry;

	private ?EventDispatcherInterface $dispatcher;

	public function __construct(
		PersisterRegistryInterface $persisterRegistry,
		RemoverRegistryInterface $removerRegistry,
		?EventDispatcherInterface $dispatcher = null
	)
	{
		$this->persisterRegistry = $persisterRegistry;
		$this->removerRegistry = $removerRegistry;
		$this->dispatcher = $dispatcher;
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		$clone = clone $image;
		$result = $this->persisterRegistry->persist($image);
		$persistent = new PersistentImage($result->getId());

		if ($clone->getFilter()) {
			$persistent = $persistent->withFilterObject($clone->getFilter());
		}

		if ($this->dispatcher) {
			$this->dispatcher->dispatch(new PersistedImageEvent($this, $clone, $persistent));
		}

		return $persistent;
	}

	public function remove(PersistentImageInterface $image): PersistentImageInterface
	{
		$clone = clone $image;
		$this->removerRegistry->remove($image);

		if ($this->dispatcher) {
			$this->dispatcher->dispatch(new RemovedImageEvent($this, $clone));
		}

		return new EmptyImage();
	}

}
