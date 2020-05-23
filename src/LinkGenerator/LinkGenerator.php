<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\LinkGenerator;

use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\FileNotFoundException;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\LinkGeneratorInterface;
use WebChemistry\ImageStorage\Resolver\DefaultImageResolverInterface;

final class LinkGenerator implements LinkGeneratorInterface
{

	private ImageStorageInterface $imageStorage;

	private FileFactoryInterface $fileFactory;

	private DefaultImageResolverInterface $defaultImageResolver;

	public function __construct(
		ImageStorageInterface $imageStorage,
		FileFactoryInterface $fileFactory,
		DefaultImageResolverInterface $defaultImageResolver
	)
	{
		$this->imageStorage = $imageStorage;
		$this->fileFactory = $fileFactory;
		$this->defaultImageResolver = $defaultImageResolver;
	}

	/**
	 * @inheritDoc
	 */
	public function link(?PersistentImageInterface $image, array $options = []): ?string
	{
		if (!$image || $image instanceof EmptyImageInterface) {
			return $this->defaultImageResolver->resolve($this, $image, $options);
		}

		$file = $this->fileFactory->create($image);
		if (!$file->exists()) {
			$image = $file->getImage();
			assert($image instanceof PersistentImageInterface);

			if (!$image->hasFilter()) {
				return $this->defaultImageResolver->resolve($this, $image, $options);
			}

			try {
				$image = $this->imageStorage->persist($image);
			} catch (FileNotFoundException $exception) {
				return $this->defaultImageResolver->resolve($this, $image, $options);
			}

			return '/' . $this->fileFactory->create($image)
				->getPath();
		}

		return '/' . $file->getPath();
	}

}
