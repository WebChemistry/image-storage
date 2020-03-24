<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Metadata;

final class LocalImageSource
{

	private ?string $path;
	private ?string $content;

	public function __construct(?string $path, ?string $content = null)
	{
		if (!$path && !$content) {
			throw new InvalidArgumentException('$path or $content must be set');
		}

		$this->path = $path;
		$this->content = $content;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function getPath(): ?string
	{
		return $this->path;
	}

}
