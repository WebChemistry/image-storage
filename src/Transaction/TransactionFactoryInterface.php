<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

interface TransactionFactoryInterface
{

	public function create(): TransactionInterface;

}
