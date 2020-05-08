<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\File\FileInterface;

interface FileNameResolver
{

	public function resolve(FileInterface $file): string;

}
