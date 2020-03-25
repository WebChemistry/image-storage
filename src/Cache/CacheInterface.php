<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Cache;

use WebChemistry\ImageStorage\Entity\ImageInterface;

interface CacheInterface
{

	public function has(ImageInterface $image): bool;

	public function save(ImageInterface $image, string $content): void;

	public function get(ImageInterface $image): string;

}
