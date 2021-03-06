<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

interface PromisedImageInterface extends PersistentImageInterface
{

	public function process(callable $action): void;

	public function isPending(): bool;

	public function then(callable $callable): void;

	public function getResult(): PersistentImageInterface;

}
