<?php declare(strict_types = 1);

namespace WebChemistry\Image\Entity;

use WebChemistry\Image\Scope\Scope;
use WebChemistry\Image\Uploader\UploaderInterface;

class StorableImage extends Image implements StorableImageInterface
{

	protected UploaderInterface $uploader;

	public function __construct(UploaderInterface $uploader, string $name, ?Scope $scope = null)
	{
		$this->uploader = $uploader;

		parent::__construct($name, $scope);
	}

	public function getUploader(): UploaderInterface
	{
		return $this->uploader;
	}

	final public function close(): void
	{
		$this->setClosed();
	}

}
