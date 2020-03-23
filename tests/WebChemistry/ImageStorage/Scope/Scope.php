<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Scope;

use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;

class Scope
{

	/** @var string[] */
	private array $scopes = [];

	final public function __construct(string ...$scopes)
	{
		foreach ($scopes as $scope) {
			$this->addScope($scope);
		}
	}

	protected function addScope(string $scope): void
	{
		$scope = trim($scope, " \t\n\r\0\v/");
		if (!$scope) {
			throw new InvalidArgumentException(sprintf('Scope must not be empty'));
		}
		if (!ctype_alnum(str_replace(['_', '-'], '', $scope))) {
			throw new InvalidArgumentException(sprintf('Scope "%s" contains invalid chars', $scope));
		}
		if ($scope[0] === '_') {
			throw new InvalidArgumentException(sprintf('Scope "%s" must not start with _', $scope));
		}

		$this->scopes[] = $scope;
	}

	/**
	 * @return static
	 */
	public function withAppendedScopes(string ...$scopes)
	{
		return new static(...$this->scopes, ...$scopes);
	}

	/**
	 * @return static
	 */
	public function withPrependedScopes(string ...$scopes)
	{
		return new static(...$scopes, ...$this->scopes);
	}

	/**
	 * @return string[]
	 */
	public function getScopes(): array
	{
		return $this->scopes;
	}

	/**
	 * @return static
	 */
	public static function fromString(string $scope)
	{
		$explode = explode('/', $scope);
		if ($explode === false) {
			return new static($scope);
		}

		return new static(...$explode);
	}

	public function toStringWithTrailingSlash(): string
	{
		return $this->scopes ? (string) $this . '/' : '';
	}

	public function toString(): string
	{
		return implode('/', $this->scopes);
	}

	public function __toString(): string
	{
		return $this->toString();
	}

}
