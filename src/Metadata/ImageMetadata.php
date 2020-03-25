<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

use finfo;
use League\Flysystem\FilesystemInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\MimeType\ImageMimeType;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;

class ImageMetadata implements ImageMetadataInterface
{

	private ImageInterface $image;
	private FilesystemInterface $filesystem;
	private PathResolverInterface $pathResolver;

	public function __construct(ImageInterface $image, FilesystemInterface $filesystem, PathResolverInterface $pathResolver)
	{
		$this->image = $image;
		$this->filesystem = $filesystem;
		$this->pathResolver = $pathResolver;
	}

	public function getImage(): ImageInterface
	{
		return $this->image;
	}

	public function getContent(): string
	{
		if ($this->image instanceof StorableImageInterface) {
			return $this->image->getUploader()->getContent();
		}

		return $this->filesystem->read($this->pathResolver->resolve($this->image->getOriginal())->toString());
	}

	public function getMimeType(): ImageMimeType
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);

		return new ImageMimeType($finfo->buffer($this->getContent()));
	}

}
