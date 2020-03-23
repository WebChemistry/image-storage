<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Uploader;

use WebChemistry\ImageStorage\Exceptions\CannotSaveFileException;
use WebChemistry\ImageStorage\Uploader\UploaderInterface;

class StringUploader implements UploaderInterface
{

	private string $content;

	public function __construct(string $content)
	{
		$this->content = $content;
	}

	public function save(string $path, string $name): void
	{
		$filePath = rtrim($path, '/') . '/' . $name;
		if (@file_put_contents($filePath, $this->content) === false) {
			throw new CannotSaveFileException(sprintf('File cannot be saved to %s', $filePath));
		}
	}

	public function getContent(): string
	{
		return $this->content;
	}

}
