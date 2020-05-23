<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;

final class TransactionFactory implements TransactionFactoryInterface
{

	private ImageStorageInterface $imageStorage;

	private FileFactoryInterface $fileFactory;

	public function __construct(ImageStorageInterface $imageStorage, FileFactoryInterface $fileFactory)
	{
		$this->imageStorage = $imageStorage;
		$this->fileFactory = $fileFactory;
	}

	public function create(): TransactionInterface
	{
		return new Transaction($this->imageStorage, $this->fileFactory);
	}

}
