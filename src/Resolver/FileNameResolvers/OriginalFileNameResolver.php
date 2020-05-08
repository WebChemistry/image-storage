<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FileNameResolvers;

use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolver;

final class OriginalFileNameResolver implements FileNameResolver
{

	public function resolve(FileInterface $file): string
	{
		return $file->getImage()->getName();
	}

}
