<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

use WebChemistry\ImageStorage\File\FileInterface;

interface FileNameResolverInterface
{

	public function resolve(FileInterface $file): string;

}
