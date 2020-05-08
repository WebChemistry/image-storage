<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filesystem;

use finfo;
use League\Flysystem\Adapter\Local;
use League\Flysystem\FileNotFoundException;
use League\Flysystem\Filesystem;
use WebChemistry\ImageStorage\Exceptions\FileException;
use WebChemistry\ImageStorage\PathInfo\PathInfoInterface;

final class LocalFilesystem implements FilesystemInterface
{

	/** @var Filesystem */
	private Filesystem $bridge;

	private string $baseDir;

	public function __construct(string $baseDir)
	{
		$this->bridge = new Filesystem(new Local($baseDir));
		$this->baseDir = rtrim($baseDir, '/');
	}

	/**
	 * @inheritDoc
	 */
	public function exists(PathInfoInterface $path): bool
	{
		return $this->bridge->has($path->toString());
	}

	/**
	 * @inheritDoc
	 */
	public function delete(PathInfoInterface $path): bool
	{
		return $this->bridge->delete($path->toString());
	}

	/**
	 * @inheritDoc
	 */
	public function put(PathInfoInterface $path, $content, array $config = []): void
	{
		$this->bridge->put($path->toString(), $content, $config);
	}

	/**
	 * @inheritDoc
	 */
	public function putWithMkdir(PathInfoInterface $path, $content, array $config = []): void
	{
		$this->bridge->createDir($path->toString($path::ALL & ~$path::IMAGE));

		$this->put($path, $content, $config);
	}

	/**
	 * @inheritDoc
	 */
	public function read(PathInfoInterface $path): string
	{
		try {
			$content = $this->bridge->read($path->toString());
		} catch (FileNotFoundException $exception) {
			throw new \WebChemistry\ImageStorage\Exceptions\FileNotFoundException($exception->getMessage());
		}

		if ($content === false) {
			throw new FileException(sprintf('Cannot read file %s', $path->toString()));
		}

		return $content;
	}

	/**
	 * @inheritDoc
	 */
	public function mimeType(PathInfoInterface $path): ?string
	{
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$mimeType = $finfo->file($this->absolutePath($path));

		return $mimeType === false ? null : $mimeType;
	}

	/**
	 * @inheritDoc
	 */
	public function absolutePath(PathInfoInterface $path): string
	{
		return $this->baseDir . '/' . ltrim($path->toString(), '/');
	}

}
