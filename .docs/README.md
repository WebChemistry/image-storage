# ImageStorage

## Content
- [Setup](#setup)
  - [Nette](#nette)
  - [Symfony](#symfony)
  - [Standalone](#standalone)
- [Usage](#usage)
  - [ImageStorage](#imagestorage)
  - [ImageInterface](#imageinterface)
  - [LinkGenerator](#linkgenerator)
  - [Storing in database](#storing-in-database)
  - [Transactions](#transactions)

## Setup

Install package

```bash
composer require contributte/image-storage
```

### Nette

Install extension
```bash
composer require contributte/image-storage-nette-extension
```

Register extension

```yaml
extensions:
  imageStorage: Contributte\ImageStorage\NetteExtension\DI\ImageStorageExtension
```

### Symfony

TODO

### Standalone

Minimal configuration

```php
use Contributte\ImageStorage\File\FileFactory;
use Contributte\ImageStorage\Filesystem\LocalFilesystem;
use Contributte\ImageStorage\Filter\VoidFilterProcessor;
use Contributte\ImageStorage\LinkGenerator\LinkGenerator;
use Contributte\ImageStorage\PathInfo\PathInfoFactory;
use Contributte\ImageStorage\Persister\EmptyImagePersister;
use Contributte\ImageStorage\Persister\PersistentImagePersister;
use Contributte\ImageStorage\Persister\PersisterRegistry;
use Contributte\ImageStorage\Persister\StorableImagePersister;
use Contributte\ImageStorage\Remover\EmptyImageRemover;
use Contributte\ImageStorage\Remover\PersistentImageRemover;
use Contributte\ImageStorage\Remover\RemoverRegistry;
use Contributte\ImageStorage\Resolver\DefaultImageResolvers\NullDefaultImageResolver;
use Contributte\ImageStorage\Resolver\FileNameResolvers\OriginalFileNameResolver;
use Contributte\ImageStorage\Storage\ImageStorage;

// filter processor
$processor = new VoidFilterProcessor();

// file and path
$fileFactory = new FileFactory(
	$filesystem = new LocalFilesystem('/path/to/root/dir'),
	$pathInfoFactory = new PathInfoFactory()
);

// default images
$defaultImageResolver = new NullDefaultImageResolver();

// persisters
$persisterRegistry = new PersisterRegistry();
$persisterRegistry->add(new EmptyImagePersister());
$persisterRegistry->add(new PersistentImagePersister($fileFactory, $processor));
$persisterRegistry->add(new StorableImagePersister($fileFactory, $processor, new OriginalFileNameResolver()));

// removers
$removerRegistry = new RemoverRegistry();
$removerRegistry->add(new EmptyImageRemover());
$removerRegistry->add(new PersistentImageRemover($fileFactory, $pathInfoFactory, $filesystem));

// storage
$storage = new ImageStorage($persisterRegistry, $removerRegistry);

// link generator
$linkGenerator = new LinkGenerator($storage, $fileFactory, $defaultImageResolver);
```

# Usage

### ImageStorage

With image storage you can save, filter (persist method) or delete (remove) images.

Saving image:

```php
use Contributte\ImageStorage\Entity\StorableImage;
use Contributte\ImageStorage\Uploader\FilePathUploader;

$image = new StorableImage(new FilePathUploader('/absolute/path/to/image.png'), 'image.png');
$persisted = $imageStorage->persist($image);
// now you cannot use $image, use $persisted instead

echo $persisted->getId();
```

Filtering image:
```php
use Contributte\ImageStorage\Entity\PersistentImage;

$image = new PersistentImage('scope/name.jpg');
$image = $image->withFilter('filterName');

$persisted = $imageStorage->persist($image);
```

Deleting image:
```php
use Contributte\ImageStorage\Entity\PersistentImage;

$image = new PersistentImage('scope/name.jpg');
$empty = $imageStorage->remove($image);
```

### ImageInterface

```php
interface ImageInterface {

    /**
     * Combination of scope and name
     * @example scope/image.jpg
     */
    public function getId(): string;

    /**
     * Name of image
     * @example image.jpg
     */
    public function getName(): string;

    /**
     * Suffix of image
     * @example jpg
     */
    public function getSuffix(): ?string;

    /**
     * Scope of image
     */
    public function getScope(): Scope;

    /**
     * Filter object of image
     */
    public function getFilter(): ?FilterInterface;

    /**
     * Checks if image has filter
     */
    public function hasFilter(): bool;

    /**
     * Checks if image is closed
     */
    public function isClosed(): bool;

    /**
     * Checks if image is empty
     */
    public function isEmpty(): bool;

    /**
     * Returns image without filter
     *
     * @return static
     */
    public function getOriginal();

}
```

### LinkGenerator
Returns full url of image if not exists use DefaultImageResolver if none is found returns null. Automatically filters image

```php
use Contributte\ImageStorage\Entity\PersistentImage;

echo $linkGenerator->link(new PersistentImage('scope/image.jpg'));
```

### Storing in database
DatabaseConverter available for these purposes

```php
use Contributte\ImageStorage\Database\DatabaseConverter;
use Contributte\ImageStorage\Entity\EmptyImage;
use Contributte\ImageStorage\Entity\PersistentImage;

// to database
$databaseConverter = new DatabaseConverter();
$database->insert([
    'id' => 1,
    'image' => $databaseConverter->convertToDatabase(new PersistentImage('scope/image.jpg')),
]);

// from database
$row = $database->fetch(1);

/** @var PersistentImage|EmptyImage $image */
$image = $databaseConverter->convertToPhp($row['image']);

/** @var PersistentImage|null $image */
$image = $databaseConverter->convertToPhp($row['image'], true);
```

### Transactions
Transactions works as image storage (implements ImageStorageInterface), but can be rollbacked

```php
use Contributte\ImageStorage\Transaction\TransactionFactory;

function extractImage(ImageStorageInterface $imageStorage) {
    return $imageStorage->persist(/*...*/);
}

$transactionFactory = new TransactionFactory($imageStorage, $fileFactory);

$image = extractImage($transaction = $transactionFactory->create()); // image didn't persist

$transaction->commit(); // image persisted

try {
    $database->insert(['image' => $image]);
} catch (Throwable $e) {
    $transaction->rollback(); // image deleted
}
```
