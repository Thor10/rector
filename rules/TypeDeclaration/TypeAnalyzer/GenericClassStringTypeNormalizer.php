<?php

declare (strict_types=1);
namespace Rector\TypeDeclaration\TypeAnalyzer;

use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ArrayType;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Constant\ConstantIntegerType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\Generic\GenericClassStringType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\StringType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use PHPStan\Type\UnionType;
use RectorPrefix20210726\Symplify\PackageBuilder\Parameter\ParameterProvider;
final class GenericClassStringTypeNormalizer
{
    /**
     * @var \PHPStan\Reflection\ReflectionProvider
     */
    private $reflectionProvider;
    /**
     * @var \Symplify\PackageBuilder\Parameter\ParameterProvider
     */
    private $parameterProvider;
    public function __construct(\PHPStan\Reflection\ReflectionProvider $reflectionProvider, \RectorPrefix20210726\Symplify\PackageBuilder\Parameter\ParameterProvider $parameterProvider)
    {
        $this->reflectionProvider = $reflectionProvider;
        $this->parameterProvider = $parameterProvider;
    }
    public function normalize(\PHPStan\Type\Type $type) : \PHPStan\Type\Type
    {
        $type = \PHPStan\Type\TypeTraverser::map($type, function (\PHPStan\Type\Type $type, $callback) : Type {
            if (!$type instanceof \PHPStan\Type\Constant\ConstantStringType) {
                return $callback($type);
            }
            $value = $type->getValue();
            // skip string that look like classe
            if ($value === 'error') {
                return $callback($type);
            }
            if (!$this->reflectionProvider->hasClass($value)) {
                return $callback($type);
            }
            return $this->resolveStringType($value);
        });
        if ($type instanceof \PHPStan\Type\UnionType) {
            return $this->resolveClassStringInUnionType($type);
        }
        return $type;
    }
    private function resolveClassStringInUnionType(\PHPStan\Type\UnionType $type) : \PHPStan\Type\Type
    {
        $unionTypes = $type->getTypes();
        foreach ($unionTypes as $unionType) {
            if (!$unionType instanceof \PHPStan\Type\ArrayType) {
                return $type;
            }
            $keyType = $unionType->getKeyType();
            $itemType = $unionType->getItemType();
            if (!$keyType instanceof \PHPStan\Type\MixedType && !$keyType instanceof \PHPStan\Type\Constant\ConstantIntegerType) {
                return $type;
            }
            if (!$itemType instanceof \PHPStan\Type\ClassStringType) {
                return $type;
            }
        }
        return new \PHPStan\Type\ArrayType(new \PHPStan\Type\MixedType(), new \PHPStan\Type\ClassStringType());
    }
    /**
     * @return \PHPStan\Type\Generic\GenericClassStringType|\PHPStan\Type\StringType
     */
    private function resolveStringType(string $value)
    {
        $classReflection = $this->reflectionProvider->getClass($value);
        if ($classReflection->isBuiltIn()) {
            return new \PHPStan\Type\Generic\GenericClassStringType(new \PHPStan\Type\ObjectType($value));
        }
        if (\strpos($value, '\\') !== \false) {
            return new \PHPStan\Type\Generic\GenericClassStringType(new \PHPStan\Type\ObjectType($value));
        }
        return new \PHPStan\Type\StringType();
    }
}
