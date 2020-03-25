<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Uploader;

use WebChemistry\ImageStorage\Exceptions\CannotSaveFileException;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

class LocalUploader implements UploaderInterface
{

	private string $filePath;

	public function __construct(string $filePath)
	{
		if (!is_file($filePath)) {
			throw new InvalidArgumentException(sprintf('"%s" is not a file or not exists', $filePath));
		}

		$this->filePath = $filePath;
	}

	public function getContent(): string
	{
		if (($content =@file_get_contents($this->filePath)) === false) {
			throw new CannotSaveFileException(sprintf('Cannot save "%s"', $this->filePath));
		}

		return $content;
	}

}
