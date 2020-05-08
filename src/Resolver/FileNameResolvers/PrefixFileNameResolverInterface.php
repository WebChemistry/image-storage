<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FileNameResolvers;

use Nette\Utils\Random;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolverInterface;

final class PrefixFileNameResolverInterface implements FileNameResolverInterface
{

	private FileFactoryInterface $fileFactory;

	public function __construct(FileFactoryInterface $fileFactory)
	{
		$this->fileFactory = $fileFactory;
	}

	public function resolve(FileInterface $file): string
	{
		$image = $file->getImage();
		$name = $image->getName();
		$final = $name;
		while ($file->exists()) {
			$image = $image->withName($final = Random::generate() . '__' . $name);
			$file = $this->fileFactory->create($image);
		}

		return $final;
	}

}
