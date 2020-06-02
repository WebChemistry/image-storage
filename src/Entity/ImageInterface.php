<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Entity;

use WebChemistry\ImageStorage\Filter\FilterInterface;
use WebChemistry\ImageStorage\Scope\Scope;

interface ImageInterface
{

	public function getId(): string;

	public function getName(): string;

	public function getSuffix(): ?string;

	public function getScope(): Scope;

	public function getFilter(): ?FilterInterface;

	public function hasFilter(): bool;

	public function isClosed(): bool;

	public function isEmpty(): bool;

	/**
	 * @return static
	 */
	public function getOriginal();

	/**
	 * @return static
	 */
	public function withName(string $name);

	/**
	 * @return static
	 */
	public function withScope(Scope $scope);

	/**
	 * @param mixed[] $options
	 * @return static
	 */
	public function withFilter(string $name, array $options = []);

	/**
	 * @return static
	 */
	public function withFilterObject(?FilterInterface $filter);

}
