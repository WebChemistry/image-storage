<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

interface PathInterface
{

	/**
	 * Scope
	 */
	public function toScope(): ?string;

	/**
	 * Scope + filter
	 */
	public function toFilter(): ?string;

	/**
	 * Scope + name
	 */
	public function toStringWithoutFilter(): string;

	/**
	 * Scope + filter + name
	 */
	public function toString(): string;

}
