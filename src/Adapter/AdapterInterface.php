<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Adapter;

use League\Flysystem\FilesystemInterface;
use WebChemistry\ImageStorage\Filter\FilterProcessorInterface;
use WebChemistry\ImageStorage\Metadata\ImageMetadataFactoryInterface;
use WebChemistry\ImageStorage\Resolver\NameResolverInterface;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;

interface AdapterInterface
{

	public function getFilesystem(): FilesystemInterface;

	public function getPathResolver(): PathResolverInterface;

	public function getNameResolver(): NameResolverInterface;

	public function getFilterProcessor(): ?FilterProcessorInterface;

	public function getBaseUrl(): string;

	public function getMetadataFactory(): ImageMetadataFactoryInterface;

}
