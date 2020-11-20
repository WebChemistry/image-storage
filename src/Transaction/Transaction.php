<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Transaction;

use Google\Cloud\Core\Exception\NotFoundException;
use InvalidArgumentException;
use Throwable;
use WebChemistry\ImageStorage\Entity\EmptyImage;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Entity\PersistentImageInterface;
use WebChemistry\ImageStorage\Entity\PromisedImage;
use WebChemistry\ImageStorage\Entity\PromisedImageInterface;
use WebChemistry\ImageStorage\Entity\StorableImage;
use WebChemistry\ImageStorage\Exceptions\FileNotFoundException;
use WebChemistry\ImageStorage\Exceptions\RollbackFailedException;
use WebChemistry\ImageStorage\Exceptions\TransactionException;
use WebChemistry\ImageStorage\File\FileFactoryInterface;
use WebChemistry\ImageStorage\ImageStorageInterface;
use WebChemistry\ImageStorage\Transaction\Entity\RemovedImage;
use WebChemistry\ImageStorage\Transaction\Entity\RemoveImage;
use WebChemistry\ImageStorage\Uploader\StringUploader;

final class Transaction implements TransactionInterface
{

	private ImageStorageInterface $imageStorage;

	private bool $commited = false;

	private FileFactoryInterface $fileFactory;

	/** @var PromisedImageInterface[] */
	private array $persist = [];

	/** @var PersistentImageInterface[] */
	private array $persisted = [];

	/** @var RemoveImage[] */
	private array $remove = [];

	/** @var RemovedImage[] */
	private array $removed = [];

	public function __construct(ImageStorageInterface $imageStorage, FileFactoryInterface $fileFactory)
	{
		$this->imageStorage = $imageStorage;
		$this->fileFactory = $fileFactory;
	}

	public function commit(): void
	{
		if ($this->commited) {
			throw new TransactionException('Transaction is already commited');
		}

		$this->commited = true;

		$this->commitRemove();
		$this->commitPersist();
	}

	/**
	 * @inheritDoc
	 */
	public function rollback(): void
	{
		if (!$this->commited) {
			throw new TransactionException('Transaction is not commited');
		}

		$exception = null;
		foreach ($this->removed as $image) {
			if (!$image->isRemoved()) {
				continue;
			}

			try {
				$store = new StorableImage(
					new StringUploader($image->getContent()),
					$image->getSource()->getName()
				);
				$store = $store->withScope($image->getSource()->getScope());

				$this->imageStorage->persist($store);
			} catch (Throwable $exception) {
				// no need
			}
		}

		foreach ($this->persisted as $image) {
			try {
				$this->imageStorage->remove($image);
			} catch (Throwable $exception) {
				// no need
			}
		}

		$this->persisted = [];
		$this->removed = [];

		if ($exception) {
			throw new RollbackFailedException(
				sprintf('Rollback failed because of: %s', $exception->getMessage()),
				0,
				$exception
			);
		}
	}

	public function persist(ImageInterface $image): PromisedImageInterface
	{
		if ($image instanceof PromisedImageInterface) {
			foreach ($this->remove as $key => $removed) {
				if ($removed->getPromisedImage() === $image) {
					unset($this->remove[$key]);

					$image->process(fn (ImageInterface $img) => $img);

					return $image;
				}
			}

			throw new InvalidArgumentException(sprintf('Cannot persist promised image twice'));
		}

		return $this->persist[] = new PromisedImage($image, false);
	}

	public function remove(PersistentImageInterface $image): PromisedImageInterface
	{
		if ($image instanceof PromisedImageInterface) {
			foreach ($this->persist as $key => $persisted) {
				if ($image === $persisted) {
					unset($this->persist[$key]);

					$image->process(fn (ImageInterface $image) => new EmptyImage(clone $image->getScope()));

					return $image;
				}
			}

			throw new InvalidArgumentException(sprintf('Cannot remove promised image twice'));
		}

		$promised = new PromisedImage($image, true);
		$this->remove[] = new RemoveImage($image, $promised);

		return $promised;
	}

	private function commitRemove(): void
	{
		foreach ($this->remove as $key => $image) {
			try {
				$this->removed[] = new RemovedImage(
					clone $image->getSource(),
					$image->getPromisedImage(),
					$this->fileFactory->create($image->getSource())->getContent()
				);
			} catch (NotFoundException | FileNotFoundException $e) {
				// object not exists continue
			}
		}

		foreach ($this->removed as $image) {
			$image->getPromisedImage()->process([$this->imageStorage, 'remove']);

			$image->setRemoved();
		}
	}

	private function commitPersist(): void
	{
		foreach ($this->persist as $image) {
			try {
				$image->process([$this->imageStorage, 'persist']);

				$this->persisted[] = $image->getResult();
			} catch (Throwable $e) {
				$this->rollback();

				throw new TransactionException('Transaction failed', 0, $e);
			}
		}
	}

}
