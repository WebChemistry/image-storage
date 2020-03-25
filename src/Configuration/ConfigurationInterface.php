<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Configuration;

use League\Flysystem\FilesystemInterface;

interface ConfigurationInterface
{

	public function getFilesystem(): FilesystemInterface;
	
	public function getBaseUrl(): string;

}
