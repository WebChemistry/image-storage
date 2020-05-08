<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FileNameResolvers;

use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolverInterface;

final class OriginalFileNameResolverInterface implements FileNameResolverInterface
{

	public function resolve(FileInterface $file): string
	{
		return $file->getImage()->getName();
	}

}
