<?php namespace Project\Tests;

use WebChemistry\ImageStorage\Exceptions\InvalidArgumentException;
use WebChemistry\ImageStorage\Scope\Scope;

class ScopeTest extends \Codeception\Test\Unit
{

	public function testOneScope()
	{
		$scope = new Scope('foo');

		$this->assertSame(['foo'], $scope->getScopes());
		$this->assertSame('foo', (string) $scope);
	}

	public function testMultiScope()
	{
		$scope = new Scope('foo', 'bar');

		$this->assertSame(['foo', 'bar'], $scope->getScopes());
		$this->assertSame('foo/bar', (string) $scope);
	}

	public function testWithAppendedScope()
	{
		$scope = new Scope('foo');
		$scope = $scope->withAppendedScopes('bar');

		$this->assertSame(['foo', 'bar'], $scope->getScopes());
		$this->assertSame('foo/bar', (string) $scope);
	}

	public function testWithPrependedScope()
	{
		$scope = new Scope('foo');
		$scope = $scope->withPrependedScopes('bar');

		$this->assertSame(['bar', 'foo'], $scope->getScopes());
		$this->assertSame('bar/foo', (string) $scope);
	}

	public function testFromString()
	{
		$scope = Scope::fromString('bar/foo');

		$this->assertSame(['bar', 'foo'], $scope->getScopes());
		$this->assertSame('bar/foo', (string) $scope);
	}

	public function testEmpty()
	{
		$this->expectException(InvalidArgumentException::class);

		new Scope('');
	}

	public function testInvalidChars()
	{
		$this->expectException(InvalidArgumentException::class);

		new Scope('bař');
	}

	public function testTrailingSlash()
	{
		$this->assertSame('foo/', (new Scope('foo'))->toStringWithTrailingSlash());
		$this->assertSame('', (new Scope())->toStringWithTrailingSlash());
	}

}
