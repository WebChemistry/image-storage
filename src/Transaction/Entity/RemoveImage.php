<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction\Entity;

use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;

/**
 * @internal
 */
final class RemoveImage
{

	private PersistentImageInterface $source;

	private PromisedImageInterface $promisedImage;

	public function __construct(PersistentImageInterface $source, PromisedImageInterface $promisedImage)
	{
		$this->source = $source;
		$this->promisedImage = $promisedImage;
	}

	public function getSource(): PersistentImageInterface
	{
		return $this->source;
	}

	public function getPromisedImage(): PromisedImageInterface
	{
		return $this->promisedImage;
	}

}
