<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction\Entity;

use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImage;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Exceptions\ImageAlreadyCommitedException;
use WebChemistry\ImageStorage\Exceptions\ImageIsNotCommitedException;
use WebChemistry\ImageStorage\Filter\FilterInterface;

class PromisedImage extends PersistentImage implements PersistentImageInterface
{

	private bool $commited = false;

	private ImageInterface $image;

	/** @var callable[] */
	private array $callbacks = [];

	public function __construct(ImageInterface $image)
	{
		$this->image = $image;
	}

	public function getId(): string
	{
		$this->throwIfNotCommited();

		return parent::getId();
	}

	public function getName(): string
	{
		$this->throwIfNotCommited();

		return parent::getName();
	}

	public function getFilter(): ?FilterInterface
	{
		$this->throwIfNotCommited();

		return parent::getFilter();
	}

	public function isCommited(): bool
	{
		return $this->commited;
	}

	public function getImage(): ImageInterface
	{
		return $this->image;
	}

	public function _commited(PersistentImageInterface $image): void
	{
		if ($this->commited) {
			throw new ImageAlreadyCommitedException('Image is already commited');
		}

		$this->commited = true;

		$this->name = $image->getName();
		$this->scope = $image->getScope();
		$this->filter = $image->getFilter();

		foreach ($this->callbacks as $callback) {
			$callback($this);
		}
	}

	/**
	 * @return static
	 */
	public function then(callable $callback)
	{
		if ($this->commited) {
			$callback($this);
		} else {
			$this->callbacks[] = $callback;
		}

		return $this;
	}

	protected function throwIfNotCommited(): void
	{
		throw new ImageIsNotCommitedException(sprintf('Image "%s" is not commited', $this->image->getId()));
	}

}
