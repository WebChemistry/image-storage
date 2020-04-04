<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Storages;

use League\Flysystem\FileNotFoundException;
use WebChemistry\ImageStorage\Adapter\AdapterInterface;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\Exceptions\NotSupportedException;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Resolver\PathInterface;

class LocalStorage implements ImageStorageInterface
{

	private AdapterInterface $adapter;
	private string $baseUrl;

	public function __construct(AdapterInterface $adapter)
	{
		$this->adapter = $adapter;
		$this->baseUrl = rtrim($adapter->getBaseUrl(), '/') . '/';
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		$argument = $image;
		$filesystem = $this->adapter->getFilesystem();

		if ($filter = $image->getFilter()) {
			if (!$this->adapter->getFilterProcessor()) {
				throw new NotSupportedException('Filter processor is not set');
			}

			$content = $this->adapter->getFilterProcessor()->process(
				$this->adapter->getMetadataFactory()->create($image)
			);
		} elseif ($image instanceof StorableImageInterface) {
			$content = $image->getUploader()->getContent();

			$image = $this->generateUniqueImage($image);
		} else {
			if (!$image->getFilter() && $image instanceof PersistentImageInterface) {
				throw new InvalidArgumentException('Cannot persist persistent image with no filter');
			}

			$content = $this->adapter->getFilesystem()->read(
				$this->getPath($image->getOriginal())
					->toString()
			);
		}

		$path = $this->getPath($image);

		$filesystem->createDir($path->toFilter());
		$filesystem->put($path->toString(), $content);

		$persistent = new PersistentImage($image->getId());

		if ($argument instanceof StorableImageInterface) {
			$argument->close();
		}

		return $persistent;
	}

	public function remove(PersistentImageInterface $image): PersistentImageInterface
	{
		$this->adapter->getFilesystem()->delete($this->getPath($image)->toString());
		if ($image instanceof PersistentImage) {
			$image->close();
		}
		
		return $image;
	}

	public function toUrl(?PersistentImageInterface $image, array $options = []): ?string
	{
		if (!$image) {
			return null;
		}
		if ($image->getFilter() && !$this->adapter->getFilterProcessor()) {
			throw new NotSupportedException('Filter processor is not set');
		}

		$path = $this->getPath($image)->toString();
		if (!$this->adapter->getFilesystem()->has($path)) {
			if (!$image->hasFilter()) {
				return null;
			}
			try {
				$this->persist($image);
			} catch (FileNotFoundException $e) {
				return null;
			}

			return $this->baseUrl. $path;
		}

		return $this->baseUrl . $path;
	}

	protected function getPath(ImageInterface $image): PathInterface
	{
		return $this->adapter->getPathResolver()->resolve($image);
	}

	protected function generateUniqueImage(StorableImageInterface $image): StorableImageInterface
	{
		$resolver = $this->adapter->getNameResolver();
		if (!$resolver->isDynamic()) {
			return $image->withName($resolver->name($image)->toString());
		}

		$original = $image;
		$path = $this->getPath($image);
		while ($this->adapter->getFilesystem()->has($path->toStringWithoutFilter())) {
			$image = $image->withName($resolver->name($original)->toString());
			$path = $this->getPath($image);
		}

		return $image;
	}

}
