<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\LinkGenerator;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\FileNotFoundException;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\LinkGeneratorInterface;

final class LinkGenerator implements LinkGeneratorInterface
{

	private ImageStorageInterface $imageStorage;

	private FileFactoryInterface $fileFactory;

	public function __construct(ImageStorageInterface $imageStorage, FileFactoryInterface $fileFactory)
	{
		$this->imageStorage = $imageStorage;
		$this->fileFactory = $fileFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function link(?PersistentImageInterface $image, array $options = []): ?string
	{
		if (!$image) {
			return null;
		}

		if ($image instanceof EmptyImageInterface) {
			return null;
		}

		$file = $this->fileFactory->create($image);
		if (!$file->exists()) {
			$image = $file->getImage();
			if (!$image->hasFilter()) {
				return null;
			}

			try {
				$image = $this->imageStorage->persist($image);
			} catch (FileNotFoundException $exception) {
				return null;
			}

			return '/' . $this->fileFactory->create($image)
				->getPath();
		}

		return '/' . $file->getPath();
	}

}
