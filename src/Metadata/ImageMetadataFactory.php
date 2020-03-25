<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

use League\Flysystem\FilesystemInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;

final class ImageMetadataFactory implements ImageMetadataFactoryInterface
{

	private FilesystemInterface $filesystem;
	private PathResolverInterface $pathResolver;

	public function __construct(FilesystemInterface $filesystem, PathResolverInterface $pathResolver)
	{
		$this->filesystem = $filesystem;
		$this->pathResolver = $pathResolver;
	}

	public function create(ImageInterface $image): ImageMetadataInterface
	{
		return new ImageMetadata($image, $this->filesystem, $this->pathResolver);
	}

}
