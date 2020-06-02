<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Persister;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolverInterface;

final class StorableImagePersister extends ImagePersisterAbstract
{

	private FileNameResolverInterface $fileNameResolver;

	public function __construct(
		FileFactoryInterface $fileFactory,
		FilterProcessorInterface $filterProcessor,
		FileNameResolverInterface $fileNameResolver
	)
	{
		parent::__construct($fileFactory, $filterProcessor);
		$this->fileNameResolver = $fileNameResolver;
	}

	public function supports(ImageInterface $image): bool
	{
		return $image instanceof StorableImageInterface;
	}

	public function persist(ImageInterface $image): ImageInterface
	{
		assert($image instanceof StorableImageInterface);

		$result = $image->withName($this->fileNameResolver->resolve($this->fileFactory->create($image)));

		$this->save($result);
		$image->close();

		return $result;
	}

}
