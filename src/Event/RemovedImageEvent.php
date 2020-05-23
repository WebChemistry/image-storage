<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;

final class RemovedImageEvent implements StoppableEventInterface
{

	use StoppableEvent;

	private ImageStorageInterface $context;

	private PersistentImageInterface $image;

	public function __construct(ImageStorageInterface $context, PersistentImageInterface $image)
	{
		$this->context = $context;
		$this->image = $image;
	}

	public function getImage(): PersistentImageInterface
	{
		return $this->image;
	}

	public function getContext(): ImageStorageInterface
	{
		return $this->context;
	}

}
