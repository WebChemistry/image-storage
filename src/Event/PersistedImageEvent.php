<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Event;

use Psr\EventDispatcher\StoppableEventInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;

final class PersistedImageEvent implements StoppableEventInterface
{

	use StoppableEvent;

	private ImageStorageInterface $context;

	private PersistentImageInterface $image;

	private ImageInterface $source;

	public function __construct(ImageStorageInterface $context, ImageInterface $source, PersistentImageInterface $image)
	{
		$this->context = $context;
		$this->source = $source;
		$this->image = $image;
	}

	public function getSource(): ImageInterface
	{
		return $this->source;
	}

	public function getResult(): PersistentImageInterface
	{
		return $this->image;
	}

	public function getContext(): ImageStorageInterface
	{
		return $this->context;
	}

}
