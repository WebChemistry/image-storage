<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Doctrine\Annotation\Compiler;

use Exception;
use LogicException;

final class ExpressionCompiler
{

	private string $string;
	private string $char;
	private int $pos;
	private string $before;
	private string $expr;
	private string $after;
	private int $length;
	private int $nesting = -1;

	public function __construct(string $string)
	{
		$this->string = $string;
	}

	public function hasExpression(): bool
	{
		return strpos($this->string, '$(') !== false;
	}

	public function compile(): ExpressionResult
	{
		if (!$this->hasExpression()) {
			throw new Exception(sprintf('String "%s" has not expression', $this->string));
		}

		$this->length = strlen($this->string);
		$this->pos = -1;

		$this->before = $this->expr = $this->after = '';

		$this->before();
		
		return new ExpressionResult($this->before, $this->expr, $this->after);
	}

	private function getChar(): ?string
	{
		$this->pos++;

		if ($this->length <= $this->pos) {
			return null;
		}

		return $this->char = $this->string[$this->pos];
	}

	private function whileCondition(): bool
	{
		return $this->length > $this->pos;
	}

	private function capture(): void
	{
		while ($this->getChar() !== null) {
			if ($this->char === '(') {
				$this->nesting++;
			}
			if ($this->char === ')') {
				$this->nesting--;
			}
			if ($this->nesting <= -1) {
				$this->after();
				break;
			}

			$this->expr .= $this->char;
		}
	}

	private function before(): void
	{
		while ($this->getChar() !== null) {
			if ($this->char === '(' && $this->pos > 0 && $this->string[$this->pos - 1] === '$') {
				$this->nesting = 0;
				$this->before = substr($this->before, 0, -1);
				$this->capture();

				break;
			}

			$this->before .= $this->char;
		}
	}

	private function after(): void
	{
		while ($this->getChar() !== null) {
			if ($this->char === '(' && $this->pos > 0 && $this->string[$this->pos - 1] === '$') {
				throw new LogicException('Only one expression allowed in annotation @ImageName');
			}
			$this->after .= $this->char;
		}
	}

}
