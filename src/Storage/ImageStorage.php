<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Storage;

use Psr\EventDispatcher\EventDispatcherInterface;
use WebChemistry\ImageStorage\Entity\EmptyImage;
use WebChemistry\ImageStorage\Entity\EmptyImageInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Event\PersistedImageEvent;
use WebChemistry\ImageStorage\Event\RemovedImageEvent;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\Filter\VoidFilterProcessor;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolverInterface;

class ImageStorage implements ImageStorageInterface
{

	private FileFactoryInterface $fileFactory;

	private FileNameResolverInterface $fileNameResolver;

	private FilterProcessorInterface $filterProcessor;

	private ?EventDispatcherInterface $dispatcher;

	public function __construct(
		FileFactoryInterface $fileFactory,
		FileNameResolverInterface $fileNameResolver,
		?FilterProcessorInterface $filterProcessor = null,
		?EventDispatcherInterface $dispatcher = null
	)
	{
		$this->fileNameResolver = $fileNameResolver;
		$this->fileFactory = $fileFactory;
		$this->filterProcessor = $filterProcessor ?? new VoidFilterProcessor();
		$this->dispatcher = $dispatcher;
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		if ($image instanceof EmptyImageInterface) {
			throw new InvalidArgumentException('Cannot persist an empty image');
		}

		$close = $image;

		$filter = $image->getFilter();
		$file = $this->fileFactory->create($image);

		if ($image instanceof StorableImageInterface) {
			$image = $image->withName($this->fileNameResolver->resolve($file));

			$file = $this->fileFactory->create($image);
		} elseif ($image instanceof PersistentImageInterface && !$filter) {
			throw new InvalidArgumentException('Cannot persist persistent image with no filter');
		}

		$this->fileFactory->create($image)
			->setContent(
				$this->filterProcessor->process($file, $this->fileFactory->create($image->getOriginal()))
			);

		$persistent = new PersistentImage($image->getId());
		if ($filter) {
			$persistent = $persistent->withFilterObject($filter);
		}

		if ($this->dispatcher) {
			$this->dispatcher->dispatch(new PersistedImageEvent($this, $close, $persistent));
		}

		if ($close instanceof StorableImageInterface) {
			$close->close();
		}

		return $persistent;
	}

	public function remove(PersistentImageInterface $image): PersistentImageInterface
	{
		if ($image instanceof EmptyImageInterface) {
			throw new InvalidArgumentException('Cannot remove an empty image');
		}

		$this->fileFactory->create($image)
			->delete();

		if ($this->dispatcher) {
			$this->dispatcher->dispatch(new RemovedImageEvent($this, $image));
		}

		if ($image instanceof PersistentImage) {
			$image->close();
		}

		return new EmptyImage();
	}

}
