<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use Throwable;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImage;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;
use WebChemistry\ImageStorage\Exceptions\NotSupportedException;
use WebChemistry\ImageStorage\Exceptions\RollbackFailedException;
use WebChemistry\ImageStorage\Exceptions\TransactionException;
use WebChemistry\ImageStorage\ImageStorageInterface;

final class Transaction implements TransactionInterface
{

	private ImageStorageInterface $imageStorage;

	private bool $commited = false;

	/** @var PromisedImageInterface[] */
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

		foreach ($this->persisted as $image) {
			try {
				$image->process([$this->imageStorage, 'persist']);
			} catch (Throwable $e) {
				$this->rollback();

				throw new TransactionException('Transaction failed', 0, $e);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function rollback(): void
	{
		if (!$this->commited) {
			throw new TransactionException('Transaction is not commited');
		}

		$exception = null;
		foreach ($this->persisted as $image) {
			if (!$image->isPending()) {
				try {
					$this->imageStorage->remove($image->getResult());
				} catch (Throwable $exception) {
					// no need
				}
			}
		}

		$this->persisted = [];

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
		throw new NotSupportedException('Not implemented');
	}

}
