<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Adapter;

use League\Flysystem\FilesystemInterface;
use WebChemistry\ImageStorage\Configuration\ConfigurationInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\Metadata\ImageMetadataFactory;
use WebChemistry\ImageStorage\Metadata\ImageMetadataFactoryInterface;
use WebChemistry\ImageStorage\Resolver\NameResolverInterface;
use WebChemistry\ImageStorage\Resolver\NameResolvers\OriginalNameResolver;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;
use WebChemistry\ImageStorage\Resolver\PathResolvers\PathResolver;

final class SimpleAdapter implements AdapterInterface
{

	private FilesystemInterface $filesystem;
	private string $baseUrl;
	private PathResolverInterface $pathResolver;
	private ?FilterProcessorInterface $filterProcessor;
	private NameResolverInterface $nameResolver;
	private ImageMetadataFactoryInterface $metadataFactory;

	public function __construct(
		ConfigurationInterface $configuration,
		?PathResolverInterface $pathResolver = null,
		?NameResolverInterface $nameResolver = null,
		FilterProcessorInterface $filterProcessor = null
	)
	{
		$this->baseUrl = $configuration->getBaseUrl();
		$this->filesystem = $configuration->getFilesystem();

		$this->pathResolver = $pathResolver ?? new PathResolver();
		$this->nameResolver = $nameResolver ?? new OriginalNameResolver();
		$this->filterProcessor = $filterProcessor;

		$this->metadataFactory = new ImageMetadataFactory($this->filesystem, $this->pathResolver);
	}

	public function getFilesystem(): FilesystemInterface
	{
		return $this->filesystem;
	}

	public function getBaseUrl(): string
	{
		return $this->baseUrl;
	}

	public function getPathResolver(): PathResolverInterface
	{
		return $this->pathResolver;
	}

	public function getNameResolver(): NameResolverInterface
	{
		return $this->nameResolver;
	}

	public function getFilterProcessor(): ?FilterProcessorInterface
	{
		return $this->filterProcessor;
	}

	public function getMetadataFactory(): ImageMetadataFactoryInterface
	{
		return $this->metadataFactory;
	}

}
