<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface PersisterInterface
{

	public function supports(ImageInterface $image): bool;

	public function persist(ImageInterface $image): ImageInterface;

}
