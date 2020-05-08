<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filesystem\League;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

final class LocalLeagueFilesystemFactory implements LeagueFilesystemFactoryInterface
{

	private string $baseDir;

	public function __construct(string $baseDir)
	{
		$this->baseDir = $baseDir;
	}

	public function needsMkDir(): bool
	{
		return true;
	}

	public function create(): FilesystemInterface
	{
		return new Filesystem(new Local($this->baseDir));
	}

}
