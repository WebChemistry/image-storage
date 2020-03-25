<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface ImageMetadataFactoryInterface
{

	public function create(ImageInterface $image): ImageMetadataInterface;

}
