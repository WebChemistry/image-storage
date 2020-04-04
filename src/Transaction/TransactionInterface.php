<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

interface TransactionInterface
{

	public function commit(): void;
	
	public function rollback(): void;

}
