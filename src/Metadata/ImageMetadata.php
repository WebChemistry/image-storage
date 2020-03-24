<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

use finfo;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\MimeType\ImageMimeType;

class ImageMetadata implements ImageMetadataInterface
{

	private ImageInterface $image;
	private LocalImageSource $source;

	public function __construct(ImageInterface $image, LocalImageSource $source)
	{
		$this->image = $image;
		$this->source = $source;
	}

	public function getImage(): ImageInterface
	{
		return $this->image;
	}

	public function getSource(): LocalImageSource
	{
		return $this->source;
	}

	public function getMimeType(): ImageMimeType
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		
		return new ImageMimeType($finfo->file($this->source->getPath()));
	}

}
