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
     * Gets a class attributes instance.
     *
     * @param ReflectionClass $class
     *
     * @return array
     * @psalm-return array<string, object>
     */
    public function getClassAttributes(ReflectionClass $class): array
    {
        return $this->convertAttributesToInstance(
            $class->getAttributes()
        );
    }

    /**
     * Gets a method attributes instance.
     *
     * @param ReflectionMethod $method
     *
     * @return array
     * @psalm-return array<string, object>
     */
    public function getMethodAttributes(ReflectionMethod $method): array
    {
        return $this->convertAttributesToInstance(
            $method->getAttributes()
        );
    }

    /**
     * Gets a property attributes instance.
     *
     * @param ReflectionProperty $property
     *
     * @return array
     * @psalm-return array<string, object>
     */
    public function getPropertyAttributes(ReflectionProperty $property): array
    {
        return $this->convertAttributesToInstance(
            $property->getAttributes()
        );
    }

    /**
     * @param ReflectionProperty $property
     * @param string $annotation
     * @psalm-param class-string<Annotation> $annotation
     *
     * @return Annotation|null
     * @psalm-return object|null
     */
    public function getPropertyAttribute(ReflectionProperty $property, string $annotation): ?object
    {
        return $this->getPropertyAttributes($property)[$annotation] ?? null;
    }

    /**
     * Convert given attributes to new instance.
     *
     * @param array<array-key, ReflectionAttribute> $attributes
     *
     * @return array
     * @psalm-return array<string, object>
     */
    private function convertAttributesToInstance(array $attributes): array
    {
        $instances = [];

        foreach ($attributes as $attribute) {
            if (!is_subclass_of($attribute->getName(), Annotation::class)) {
                continue;
            }

            $instances[$attribute->getName()] = $attribute->newInstance();
        }

        return $instances;
    }
}
