<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\File;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Filesystem\FilesystemInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactoryInterface;

final class FileFactory implements FileFactoryInterface
{

	private FilesystemInterface $filesystem;

	private PathInfoFactoryInterface $pathInfoFactory;

	public function __construct(FilesystemInterface $filesystem, PathInfoFactoryInterface $pathInfoFactory)
	{
		$this->filesystem = $filesystem;
		$this->pathInfoFactory = $pathInfoFactory;
	}

	public function create(ImageInterface $image): FileInterface
	{
		if ($image instanceof FileProviderInterface) {
			return $image->provideFile();
		}

		return new File($image, $this->filesystem, $this->pathInfoFactory->create($image));
	}

}
