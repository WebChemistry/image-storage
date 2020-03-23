<?php declare(strict_types = 1);

namespace WebChemistry\Image\Entity;

use WebChemistry\Image\Scope\Scope;

class PersistentImage extends Image implements PersistentImageInterface
{

	public function __construct(string $id)
	{
		parent::__construct(...$this->parseId($id));
	}

	/**
	 * @return mixed[]
	 */
	protected function parseId(string $id): array
	{
		$explode = explode('/', $id);
		$last = array_key_last($explode);
		$name = $explode[$last];
		unset($explode[$last]);

		return [$name, new Scope(...$explode)];
	}

}
