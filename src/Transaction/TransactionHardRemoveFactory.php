<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use WebChemistry\ImageStorage\ImageStorageInterface;

final class TransactionHardRemoveFactory implements TransactionHardRemoveFactoryInterface
{

	/** @var ImageStorageInterface */
	private ImageStorageInterface $imageStorage;

	public function __construct(ImageStorageInterface $imageStorage)
	{
		$this->imageStorage = $imageStorage;
	}

	public function create(): TransactionHardRemoveInterface
	{
		return new TransactionHardRemove($this->imageStorage);
	}

}
