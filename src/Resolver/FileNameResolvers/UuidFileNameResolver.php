<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver\FileNameResolvers;

use Ramsey\Uuid\Uuid;
use WebChemistry\ImageStorage\File\FileInterface;
use WebChemistry\ImageStorage\Resolver\FileNameResolver;

final class UuidFileNameResolver implements FileNameResolver
{

	public function resolve(FileInterface $file): string
	{
		return Uuid::uuid4()->toString();
	}

}
