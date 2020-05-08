<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\File;

interface FileProviderInterface
{

	public function provideFile(): FileInterface;

}
