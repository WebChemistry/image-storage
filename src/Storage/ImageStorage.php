<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Storage;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\Exceptions\NotSupportedException;
use WebChemistry\ImageStorage\File\FileFactory;
use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\File\FileProviderInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolver;

class ImageStorage implements ImageStorageInterface
{

	private FileFactory $fileFactory;

	private FileNameResolver $fileNameResolver;

	private ?FilterProcessorInterface $filterProcessor;

	public function __construct(
		FileFactory $fileFactory,
		FileNameResolver $fileNameResolver,
		?FilterProcessorInterface $filterProcessor = null
	)
	{
		$this->fileNameResolver = $fileNameResolver;
		$this->fileFactory = $fileFactory;
		$this->filterProcessor = $filterProcessor;
	}

	protected function createFile(ImageInterface $image): FileInterface
	{
		if ($image instanceof FileProviderInterface) {
			return $image->provideFile();
		}

		return $this->fileFactory->create($image);
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		$original = $image;

		if ($filter = $image->getFilter()) {
			if (!$this->filterProcessor) {
				throw new NotSupportedException('Filter processor is not set');
			}

			$content = $this->filterProcessor->process(
				$filter,
				$this->createFile($image),
				$this->createFile($image->getOriginal())
			);
		} elseif ($image instanceof StorableImageInterface) {
			$content = $image->getUploader()->getContent();

			$image = $image->withName($this->fileNameResolver->resolve($this->createFile($image)));
		} else {
			throw new InvalidArgumentException('Cannot persist persistent image with no filter');
		}

		$this->createFile($image)
			->setContent($content);

		$persistent = new PersistentImage($original->getId());
		if ($original instanceof StorableImageInterface) {
			$original->close();
		} elseif ($filter) {
			$persistent = $persistent->withFilterObject($filter);
		}

		return $persistent;
	}

	public function remove(PersistentImageInterface $image): PersistentImageInterface
	{
		$this->createFile($image)
			->delete();

		if ($image instanceof PersistentImage) {
			$image->close();
		}

		return $image;
	}

}
