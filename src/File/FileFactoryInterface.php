<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\File;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface FileFactoryInterface
{

	public function create(ImageInterface $image): FileInterface;

}
