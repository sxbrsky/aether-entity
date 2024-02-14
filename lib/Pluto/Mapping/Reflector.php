<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Mapping;

use Pluto\Mapping\Annotations\Annotation;
use Nulldark\Tests\Stubs\AnnotationDummyClass;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Mapping
 * @since 0.1.0
 */
final class Reflector
{
    /**
     * Gets a parent classes for given class.
     *
     * @param string $classname
     *
     * @return array
     * @psalm-return array<array-key, class-string>
     */
    public function getParentClasses(string $classname): array {
        $parents = class_parents($classname);

        return $parents !== false
            ? $parents : [];
    }

    /**
     * Gets a class annotations.
     *
     * @param ReflectionClass $class
     *
     * @return array<string, T>
     * @psalm-return array<class-string<T>, T>
     *
     * @template T of Annotation
     */
    public function getClassAnnotations(ReflectionClass $class): array {
        return $this->convertAnnotationsToInstance(
            $class->getAttributes()
        );
    }

    /**
     * Gets a method annotations.
     *
     * @param ReflectionMethod $method
     *
     * @return array<string, Annotation>
     * @psalm-return array<class-string, Annotation>
     */
    public function getMethodAnnotations(ReflectionMethod $method): array {
        return $this->convertAnnotationsToInstance(
            $method->getAttributes()
        );
    }

    /**
     * Gets a property annotations.
     *
     * @param ReflectionProperty $property
     *
     * @return array<string, Annotation>
     * @psalm-return array<class-string, Annotation>
     */
    public function getPropertyAnnotations(ReflectionProperty $property): array {
        return $this->convertAnnotationsToInstance(
            $property->getAttributes()
        );
    }

    /**
     * Gets single property annotation.
     *
     * @param ReflectionProperty    $property
     * @param string                $annotation
     * @psalm-param class-string<T> $annotation
     *
     * @return T|null
     * @psalm-return T|null
     *
     * @template T of Annotation
     */
    public function getPropertyAnnotation(ReflectionProperty $property, string $annotation) {
        return $this->getPropertyAnnotations($property)[$annotation] ?? null;
    }

    /**
     * Convert given annotations to new instance.
     **
     * @param array<ReflectionAttribute> $attributes
     *
     * @return array<class-string<T>, T>
     * @psalm-return array<class-string<T>, T>
     *
     * @template T of Annotation
     */
    private function convertAnnotationsToInstance(array $attributes): array {
        $instances = [];

        foreach ($attributes as $attribute) {
            if (!is_subclass_of($attribute->getName(), Annotation::class)) {
                continue;
            }

            $instance = $attribute->newInstance();
            assert($instance instanceof Annotation);

            $instances[$attribute->getName()] = $instance;
        }

        return $instances;
    }
}
