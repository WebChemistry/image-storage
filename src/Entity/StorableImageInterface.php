<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

use WebChemistry\ImageStorage\Uploader\UploaderInterface;

interface StorableImageInterface extends ImageInterface
{

	public function getUploader(): UploaderInterface;

	public function close(?string $reason = null): void;

	public function isClosed(): bool;

}
