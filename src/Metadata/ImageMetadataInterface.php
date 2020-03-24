<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\MimeType\ImageMimeType;

interface ImageMetadataInterface
{

	public function getImage(): ImageInterface;

	/**
	 * @return mixed
	 */
	public function getSource();

	public function getMimeType(): ImageMimeType;

}
