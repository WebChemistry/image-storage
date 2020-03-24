<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filter;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Metadata\ImageMetadataInterface;

interface FilterProcessorInterface
{

	public function process(ImageMetadataInterface $metadata, ?string $savePath = null, array $options = []): ?string;

}
