<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use WebChemistry\ImageStorage\ImageStorageInterface;

final class TransactionFactory implements TransactionFactoryInterface
{

	private ImageStorageInterface $imageStorage;

	public function __construct(ImageStorageInterface $imageStorage)
	{
		$this->imageStorage = $imageStorage;
	}

	public function create(): TransactionInterface
	{
		return new Transaction($this->imageStorage);
	}

}
