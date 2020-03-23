<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Storages;

use Symfony\Component\Filesystem\Filesystem;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImageInterface;
use WebChemistry\ImageStorage\Exceptions\NotSupportedException;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Naming\ImageNamerInterface;
use WebChemistry\ImageStorage\Naming\ImageNamers\OriginalImageNamer;
use WebChemistry\ImageStorage\Resolver\AbsolutePath;
use WebChemistry\ImageStorage\Resolver\PathInterface;
use WebChemistry\ImageStorage\Resolver\PathResolver;
use WebChemistry\ImageStorage\Resolver\PathResolverInterface;

class SimpleLocalStorage implements ImageStorageInterface
{

	protected string $directory;
	protected string $baseUrl;
	protected ImageNamerInterface $namer;
	protected PathResolverInterface $pathResolver;
	protected Filesystem $filesystem;

	public function __construct(
		string $directory,
		string $baseUrl,
		?ImageNamerInterface $namer = null,
		?PathResolverInterface $pathResolver = null
	)
	{
		$this->directory = rtrim($directory, '/');
		$this->baseUrl = rtrim($baseUrl, '/');
		$this->namer = $namer ?? new OriginalImageNamer();
		$this->pathResolver = $pathResolver ?? new PathResolver();
		$this->filesystem = new Filesystem();
	}

	public function persist(StorableImageInterface $image): PersistentImageInterface
	{
		if ($image->getFilter()) {
			throw new NotSupportedException('Simple storage not support filters');
		}

		$image = $this->generateUniqueImage($image);
		$path = $this->getAbsolutePath($image);
		$absolutePath = $image instanceof StorableImageInterface ? $path->toScope() : $path->toFilter();

		$this->filesystem->mkdir($absolutePath);

		$image->getUploader()->save($absolutePath, $image->getName());

		$persistent = new PersistentImage($image->getId());

		$image->close();

		return $persistent;
	}

	public function remove(PersistentImageInterface $image): void
	{
		$this->filesystem->remove($this->getAbsolutePath($image)->toString());
	}

	public function toUrl(?PersistentImageInterface $image, array $options = []): ?string
	{
		if (!$image) {
			return null;
		}
		if ($image->getFilter()) {
			throw new NotSupportedException('Simple storage not support filters');
		}

		return $this->getAbsoluteBaseUrl($image)->toString();
	}

	protected function getAbsolutePath(ImageInterface $image): PathInterface
	{
		return new AbsolutePath($this->directory, $this->pathResolver->resolve($image));
	}

	protected function getAbsoluteBaseUrl(ImageInterface $image): PathInterface
	{
		return new AbsolutePath($this->baseUrl, $this->pathResolver->resolve($image));
	}

	protected function generateUniqueImage(StorableImageInterface $image): StorableImageInterface
	{
		if (!$this->namer->isDynamic()) {
			return $image->withName($this->namer->name($image)->toString());
		}

		$original = $image;
		$path = $this->getAbsolutePath($image);
		while (file_exists($path->toStringWithoutFilter())) {
			$image = $image->withName($this->namer->name($original)->toString());
			$path = $this->getAbsolutePath($image);
		}

		return $image;
	}

}
