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
use ReflectionException;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Mapping
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

        $classAnnotations = $this->reflector->getClassAnnotations($reflectionClass);

        if (isset($classAnnotations[Annotations\Entity::class])) {
            /** @var Annotations\Entity $entityAnnotation */
            $entityAnnotation = $classAnnotations[Annotations\Entity::class];

            if ($entityAnnotation->repositoryClass !== null) {
                $class->setCustomRepository($entityAnnotation->repositoryClass);
            }
        }

        if (isset($classAnnotations[Annotations\Table::class])) {
            /** @var Annotations\Table $tableAnnotation */
            $tableAnnotation = $classAnnotations[Annotations\Table::class];
            $class->setPrimaryTable($tableAnnotation);
        }

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
