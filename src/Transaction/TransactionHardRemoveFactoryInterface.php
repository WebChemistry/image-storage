<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

interface TransactionHardRemoveFactoryInterface
{

	public function create(): TransactionHardRemoveInterface;

}
