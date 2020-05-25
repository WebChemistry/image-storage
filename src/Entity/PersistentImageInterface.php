<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

interface PersistentImageInterface extends ImageInterface
{

	public function close(): void;

}
