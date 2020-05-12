<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImage;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;

final class TransactionHardRemove implements TransactionHardRemoveInterface
{

	private ImageStorageInterface $imageStorage;

	private Transaction $transaction;

	/** @var PromisedImageInterface[] */
	private array $remove;

	public function __construct(ImageStorageInterface $imageStorage)
	{
		$this->imageStorage = $imageStorage;
		$this->transaction = new Transaction($this->imageStorage);
	}

	public function commit(): void
	{
		$this->transaction->commit();
	}

	public function commitHardRemove(): void
	{
		foreach ($this->remove as $image) {
			$image->process([$this->imageStorage, 'remove']);
		}
	}

	public function rollback(): void
	{
		$this->transaction->rollback();
	}

	public function persist(ImageInterface $image): PromisedImageInterface
	{
		return $this->transaction->persist($image);
	}

	public function remove(PersistentImageInterface $image): PromisedImageInterface
	{
		return $this->remove[] = new PromisedImage($image);
	}

}
