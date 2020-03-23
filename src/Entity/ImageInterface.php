<?php declare(strict_types = 1);

namespace WebChemistry\Image\Entity;

use WebChemistry\Image\Filter\Filter;
use WebChemistry\Image\Scope\Scope;

interface ImageInterface
{

	public function getId(): string;

	public function getName(): string;

	public function getScope(): Scope;

	public function getFilter(): ?Filter;

	/**
	 * @return static
	 */
	public function withName(string $name);

	/**
	 * @param mixed[] $options
	 * @return static
	 */
	public function withFilter(string $name, array $options = []);

	/**
	 * @return static
	 */
	public function withFilterObject(Filter $filter);

}
