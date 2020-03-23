<?php declare(strict_types = 1);

namespace WebChemistry\Image\Entity;

use WebChemistry\Image\Uploader\UploaderInterface;

interface StorableImageInterface extends ImageInterface
{

	public function getUploader(): UploaderInterface;

	public function close(): void;

	public function isClosed(): bool;

}
