<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use Throwable;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\NotSupportedException;
use WebChemistry\ImageStorage\Exceptions\RollbackFailedException;
use WebChemistry\ImageStorage\Exceptions\TransactionException;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\UnitOfWork;

final class Transaction implements TransactionInterface, UnitOfWork
{

	private ImageStorageInterface $imageStorage;
	private bool $commited = false;

	/** @var PromisedImage[] */
	private array $persisted = [];

	public function __construct(ImageStorageInterface $imageStorage)
	{
		$this->imageStorage = $imageStorage;
	}

	public function commit(): void
	{
		if ($this->commited) {
			throw new TransactionException('Transaction is already commited');
		}
		$this->commited = true;

		$process = [];
		foreach ($this->persisted as $persited) {
			try {
				$image = $this->imageStorage->persist($persited);
			} catch (Throwable $e) {
				$this->rollback();
				throw new TransactionException('Transaction failed', 0, $e);
			}
			$process[] = [$persited, $image];
		}

		try {
			foreach ($process as [$persisted, $image]) {
				$persited->_commited($image);
			}
		} catch (Throwable $e) {
			$this->rollback();
			throw new TransactionException('Transaction failed', 0, $e);
		}
	}

	public function rollback(): void
	{
		if (!$this->commited) {
			throw new TransactionException('Transaction is not commited');
		}

		$exception = null;
		foreach ($this->persisted as $persisted) {
			if ($persisted->isCommited()) {
				try {
					$this->imageStorage->remove($persisted);
				} catch (Throwable $e) {
					$exception = $e;
				}
			}
		}
		
		if ($exception) {
			throw new RollbackFailedException('Rollback failed', 0, $exception);
		}
	}

	public function persist(ImageInterface $image): PersistentImageInterface
	{
		return $this->persisted[] = new PromisedImage($image);
	}

	public function remove(PersistentImageInterface $image): PersistentImageInterface
	{
		throw new NotSupportedException('Cannot use remove in transaction');
	}

}
