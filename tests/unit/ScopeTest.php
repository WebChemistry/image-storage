<?php declare(strict_types = 1);

namespace Project\Tests;

use Codeception\Test\Unit;
use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\Scope\Scope;

class ScopeTest extends Unit
{

	public function testOneScope(): void
	{
		$scope = new Scope('foo');

		$this->assertSame(['foo'], $scope->getScopes());
		$this->assertSame('foo', (string) $scope);
	}

	public function testMultiScope(): void
	{
		$scope = new Scope('foo', 'bar');

		$this->assertSame(['foo', 'bar'], $scope->getScopes());
		$this->assertSame('foo/bar', (string) $scope);
	}

	public function testWithAppendedScope(): void
	{
		$scope = new Scope('foo');
		$scope = $scope->withAppendedScopes('bar');

		$this->assertSame(['foo', 'bar'], $scope->getScopes());
		$this->assertSame('foo/bar', (string) $scope);
	}

	public function testWithPrependedScope(): void
	{
		$scope = new Scope('foo');
		$scope = $scope->withPrependedScopes('bar');

		$this->assertSame(['bar', 'foo'], $scope->getScopes());
		$this->assertSame('bar/foo', (string) $scope);
	}

	public function testFromString(): void
	{
		$scope = Scope::fromString('bar/foo');

		$this->assertSame(['bar', 'foo'], $scope->getScopes());
		$this->assertSame('bar/foo', (string) $scope);
	}

	public function testEmpty(): void
	{
		$this->expectException(InvalidArgumentException::class);

		new Scope('');
	}

	public function testInvalidChars(): void
	{
		$this->expectException(InvalidArgumentException::class);

		new Scope('bař');
	}

	public function testTrailingSlash(): void
	{
		$this->assertSame('foo/', (new Scope('foo'))->toStringWithTrailingSlash());
		$this->assertSame('', (new Scope())->toStringWithTrailingSlash());
	}

}
