<?php declare(strict_types = 1);

namespace WebChemistry\Image\Uploader;

use WebChemistry\Image\Exceptions\CannotSaveFileException;
use WebChemistry\Image\Uploader\UploaderInterface;

class CopyUploader implements UploaderInterface
{

	private string $filePath;

	public function __construct(string $filePath)
	{
		$this->filePath = $filePath;
	}

	public function save(string $path, string $name): void
	{
		if (!@copy($this->filePath, $dest = rtrim($path, '/') . '/' . $name)) {
			throw new CannotSaveFileException(sprintf('Cannot copy image "%s" to "%s"', $this->filePath, $dest));
		}
	}

	public function getContent(): string
	{
		return file_get_contents($this->filePath);
	}

}
