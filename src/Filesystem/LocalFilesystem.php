<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filesystem;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

final class LocalFilesystem extends FilesystemAbstract
{

	public function __construct(string $root)
	{
		parent::__construct(new Filesystem(new Local($root)));
	}

}
