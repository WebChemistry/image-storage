<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

interface TransactionHardRemoveInterface extends TransactionInterface
{

	public function commitHardRemove(): void;

}
