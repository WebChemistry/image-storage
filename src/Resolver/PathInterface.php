<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Resolver;

interface PathInterface
{

	/**
	 * Bucket
	 */
	public function toBucket(): string;

	/**
	 * Bucket + Scope
	 */
	public function toScope(): string;

	/**
	 * Bucket + Scope + filter
	 */
	public function toFilter(): string;

	/**
	 * Bucket + Scope + name
	 */
	public function toStringWithoutFilter(): string;

	/**
	 * Scope + filter + name
	 */
	public function toString(): string;

}
