<?php declare(strict_types = 1);

namespace WebChemistry\ImageStorage\Doctrine\Annotation;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use WebChemistry\ImageStorage\Doctrine\Annotation\Compiler\ExpressionCompiler;
use WebChemistry\ImageStorage\Entity\ImageInterface;
use WebChemistry\ImageStorage\Scope\Scope;

class ImageScopeFromAnnotation
{

	private Reader $reader;

	public function __construct(Reader $reader)
	{
		$this->reader = $reader;
	}

	public function process(ImageInterface $image, string $class, string $property): ImageInterface
	{
		$class = new ReflectionClass($class);
		$property = $class->getProperty($property);

		return $this->processFromReflection($image, $property);
	}

	public function processFromReflection(ImageInterface $image, ReflectionProperty $property): ImageInterface
	{
		$scopes = $this->getScopes($property);
		if ($scopes === null) {
			return $image;
		}
		$scope = new Scope(...$scopes);

		return $image->withScope($scope);
	}

	public function processWithVariables(
		ImageInterface $image,
		object $context,
		string $property,
		array $variables = []
	): ImageInterface
	{
		$class = new ReflectionClass(get_class($context));
		$property = $class->getProperty($property);

		$variables['self'] = $context;

		$scopes = $this->getScopes($property);
		$expression = new ExpressionLanguage();
		foreach ($scopes as $index => $scope) {
			$compiler = new ExpressionCompiler($scope);
			if ($compiler->hasExpression()) {
				$result = $compiler->compile();

				$scopes[$index] = $result->before . $expression->evaluate($result->expr, $variables) . $result->after;
			}
		}

		return $image->withScope(new Scope(...$scopes));
	}

	/**
	 * @return string[]|null
	 */
	protected function getScopes(ReflectionProperty $property): ?array
	{
		/** @var ImageScope|null $annotation */
		$annotation = $this->reader->getPropertyAnnotation($property, ImageScope::class);
		if (!$annotation) {
			return null;
		}

		return $annotation->getScopes();
	}

}
