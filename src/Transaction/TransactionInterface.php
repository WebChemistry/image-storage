<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;
use WebChemistry\ImageStorage\Exceptions\RollbackFailedException;
use WebChemistry\ImageStorage\Exceptions\TransactionException;
use WebChemistry\ImageStorage\ImageStorageInterface;

interface TransactionInterface extends ImageStorageInterface
{

	public function commit(): void;

	/**
	 * @throws RollbackFailedException
	 * @throws TransactionException
	 */
	public function rollback(): void;

	public function persist(ImageInterface $image): PromisedImageInterface;

	public function remove(PersistentImageInterface $image): PromisedImageInterface;

}
