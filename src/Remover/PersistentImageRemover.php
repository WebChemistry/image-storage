<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Remover;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\Filesystem\FilesystemInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfoFactoryInterface;

final class PersistentImageRemover implements RemoverInterface
{

	private FileFactoryInterface $fileFactory;

	private PathInfoFactoryInterface $pathInfoFactory;

	private FilesystemInterface $filesystem;

	public function __construct(FileFactoryInterface $fileFactory, PathInfoFactoryInterface $pathInfoFactory, FilesystemInterface $filesystem)
	{
		$this->fileFactory = $fileFactory;
		$this->pathInfoFactory = $pathInfoFactory;
		$this->filesystem = $filesystem;
	}

	public function supports(PersistentImageInterface $image): bool
	{
		return !$image instanceof EmptyImageInterface;
	}

	public function remove(PersistentImageInterface $image): void
	{
		$this->removeOriginal($image);
		$this->removeFiltered($image);

		$image->close();
	}

	private function removeFiltered(PersistentImageInterface $image): void
	{
		$path = $this->pathInfoFactory->create($image->withFilter('void'));

		foreach ($this->filesystem->listContents($path->toString($path::BUCKET | $path::SCOPE)) as $path) {
			if ($path['type'] !== 'dir') {
				continue;
			}

			if (!$path['filename'] || $path['filename'][0] !== '_') {
				continue;
			}

			$this->fileFactory->create($image->withFilter(substr($path['filename'], 1)))
				->delete();
		}
	}

	private function removeOriginal(PersistentImageInterface $image): void
	{
		$this->fileFactory->create($image->getOriginal())
			->delete();
	}

}
