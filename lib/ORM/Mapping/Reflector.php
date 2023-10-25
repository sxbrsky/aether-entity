<?php

/**
 * Copyright (c) 2023 Dominik Szamburski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Nulldark\ORM\Mapping;

use Nulldark\ORM\Mapping\Annotations\Annotation;
use Nulldark\Tests\Stubs\AnnotationDummyClass;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\Mapping
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
    public function getParentClasses(string $classname): array
    {
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
    public function getClassAnnotations(ReflectionClass $class): array
    {
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
    public function getMethodAnnotations(ReflectionMethod $method): array
    {
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
    public function getPropertyAnnotations(ReflectionProperty $property): array
    {
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
    public function getPropertyAnnotation(ReflectionProperty $property, string $annotation)
    {
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
    private function convertAnnotationsToInstance(array $attributes): array
    {
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
