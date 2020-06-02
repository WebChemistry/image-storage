<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;

abstract class ImagePersisterAbstract implements PersisterInterface
{

	protected FileFactoryInterface $fileFactory;

	protected FilterProcessorInterface $filterProcessor;

	public function __construct(FileFactoryInterface $fileFactory, FilterProcessorInterface $filterProcessor)
	{
		$this->fileFactory = $fileFactory;
		$this->filterProcessor = $filterProcessor;
	}

	protected function save(ImageInterface $image): void
	{
		$target = $this->fileFactory->create($image);
		$source = $this->fileFactory->create($image->getOriginal());

		$target->setContent(
			$this->filterProcessor->process($target, $source)
		);
	}

}
