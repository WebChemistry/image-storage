<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Filesystem;

use League\Flysystem\FileNotFoundException;
use League\Flysystem\FilesystemInterface as LeagueFilesystemInterface;
use WebChemistry\ImageStorage\Exceptions\FileException;
use WebChemistry\ImageStorage\Filesystem\League\LeagueFilesystemFactoryInterface;
use WebChemistry\ImageStorage\PathInfo\PathInfoInterface;

final class LocalFilesystem implements FilesystemInterface
{

	private LeagueFilesystemInterface $bridge;

	private bool $mkdir;

	public function __construct(LeagueFilesystemFactoryInterface $leagueFilesystemFactory)
	{
		$this->bridge = $leagueFilesystemFactory->create();
		$this->mkdir = $leagueFilesystemFactory->needsMkDir();
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
		if ($this->mkdir) {
			$this->bridge->createDir($path->toString($path::ALL & ~$path::IMAGE));
		}

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
		$mimeType = $this->bridge->getMimetype($path->toString());

		return $mimeType === false ? null : $mimeType;
	}

}
