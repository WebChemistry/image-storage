<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Doctrine\Annotation\Compiler;

class ExpressionResult
{

	public string $before;
	public string $expr;
	public string $after;
	
	public function __construct(string $before, string $expr, string $after)
	{
		$this->before = $before;
		$this->expr = $expr;
		$this->after = $after;
	}

}
