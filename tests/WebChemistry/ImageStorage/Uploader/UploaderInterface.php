<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Uploader;

interface UploaderInterface
{

	public function save(string $path, string $name): void;

	public function getContent(): string;

}
