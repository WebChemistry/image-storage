<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

use WebChemistry\ImageStorage\Exceptions\EmptyImageException;

final class EmptyImage extends Image implements EmptyImageInterface
{

	public function __construct()
	{
	}

	public function getId(): string
	{
		throw new EmptyImageException(sprintf('Cannot call %s on empty image', __METHOD__));
	}

	public function getName(): string
	{
		throw new EmptyImageException(sprintf('Cannot call %s on empty image', __METHOD__));
	}

	public function getSuffix(): ?string
	{
		throw new EmptyImageException(sprintf('Cannot call %s on empty image', __METHOD__));
	}

	public function getOriginal()
	{
		throw new EmptyImageException(sprintf('Cannot call %s on empty image', __METHOD__));
	}

}
