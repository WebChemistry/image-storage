<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filesystem\League;

use League\Flysystem\FilesystemInterface;

interface LeagueFilesystemFactoryInterface
{

	public function create(): FilesystemInterface;

}
