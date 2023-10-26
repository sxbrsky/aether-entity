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
use ReflectionException;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\Mapping
 * @since 0.1.0
 */
class MetadataFactory
{
    /** @var array<string, Metadata> $metadataCache */
    private array $metadataCache = [];

    private Reflector $reflector;

    public function __construct()
    {
        $this->reflector = new Reflector();
    }

    /**
     * Gets metadata for given class.
     *
     * @param string $classname
     * @psalm-param class-string<T> $classname
     *
     * @return Metadata
     *
     * @template T of object
     *
     * @throws ReflectionException
     */
    public function getMedataFor(string $classname): Metadata
    {
        $classname = $this->normalizeClassname($classname);

        if (isset($this->metadataCache[$classname])) {
            return $this->metadataCache[$classname];
        }

        $class = $this->newMetadataInstance($classname);

        $this->doLoadMetadata($class);

        return $this->metadataCache[$classname] = $class;
    }

    /**
     * Loads metadata based on $class.
     *
     * @param Metadata $class
     * @return void
     *
     * @throws ReflectionException
     */
    private function doLoadMetadata(Metadata $class): void
    {
        $reflectionClass = new \ReflectionClass($class->name);

        $classAnnotations = $this->reflector->getClassAnnotations($reflectionClass,);

        /** @var Annotations\Table $tableAnnotation */
        $tableAnnotation = $classAnnotations[Annotations\Table::class];
        $class->setPrimaryTable($tableAnnotation);

        foreach ($reflectionClass->getProperties() as $property) {
            $class->setFieldMapping(
                $property,
                $this->reflector->getPropertyAnnotation($property, Annotations\Column::class),
                $this->reflector->getPropertyAnnotation($property, Annotations\Id::class)
            );

            $class->properties[$property->name] = $property;
        }
    }

    /**
     * Normalizes class name.
     *
     * @param class-string $className
     * @return class-string
     */
    private function normalizeClassname(string $className): string
    {
        return ltrim($className, '\\');
    }

    /**
     * Initialize new Metadata instance.
     *
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return Metadata
     *
     * @template T of object
     */
    private function newMetadataInstance(string $classname): Metadata
    {
        return new Metadata(
            $classname
        );
    }
}
