<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Uploader;

class StringUploader implements UploaderInterface
{

	private string $content;

	public function __construct(string $content)
	{
		$this->content = $content;
	}

	public function getContent(): string
	{
		return $this->content;
	}

}
