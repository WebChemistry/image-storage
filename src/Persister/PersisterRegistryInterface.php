<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface PersisterRegistryInterface
{

	public function add(PersisterInterface $persister): void;

	public function persist(ImageInterface $image): ImageInterface;

}
