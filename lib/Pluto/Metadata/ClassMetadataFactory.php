<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Pluto\Metadata;

class ClassMetadataFactory
{
    /** @var array<string, ClassMetadata> $metadataCache */
    private array $metadataCache = [];

    private Reflector $reflector;

    public function __construct() {
        $this->reflector = new Reflector();
    }

    /**
     * Gets class metadata for given class.
     *
     * @param class-string<T> $classname
     *  The name of the class.
     *
     * @return \Pluto\Metadata\ClassMetadata
     *  Returns class metadata for given class.
     *
     * @throws \ReflectionException
     *
     * @template T of object
     */
    public function getMedataFor(string $classname): ClassMetadata {
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
     * @param \Pluto\Metadata\ClassMetadata $class
     *  The class metadata.
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    private function doLoadMetadata(ClassMetadata $class): void {
        $reflectionClass = new \ReflectionClass($class->name);

        $classAttributes = $this->reflector->getClassAttributes($reflectionClass);

        if (isset($classAttributes[\Pluto\Attributes\Entity::class])) {
            /** @var \Pluto\Attributes\Entity $entityAnnotation */
            $entityAnnotation = $classAttributes[\Pluto\Attributes\Entity::class];

            if ($entityAnnotation->repositoryClass !== null) {
                $class->setCustomRepository($entityAnnotation->repositoryClass);
            }
        }

        if (isset($classAttributes[\Pluto\Attributes\Table::class])) {
            /** @var \Pluto\Attributes\Table $tableAttribute */
            $tableAttribute = $classAttributes[\Pluto\Attributes\Table::class];
            $class->setPrimaryTable($tableAttribute);
        }

        foreach ($reflectionClass->getProperties() as $property) {
            $class->setFieldMapping(
                $property,
                $this->reflector->getPropertyAttribute($property, \Pluto\Attributes\Column::class),
                $this->reflector->getPropertyAttribute($property, \Pluto\Attributes\Id::class)
            );

            $class->properties[$property->name] = $property;
        }
    }

    /**
     * Normalizes class name.
     *
     * @param class-string $classname
     *  The class name of entity to normalize.
     *
     * @return class-string
     *  Returns normalized class name.
     */
    private function normalizeClassname(string $classname): string {
        return \ltrim($classname, '\\');
    }

    /**
     * Initialize new Metadata instance.
     *
     * @param class-string<T> $classname
     *  The class name of entity to initialize.
     *
     * @return \Pluto\Metadata\ClassMetadata
     *  Returns the class metadata.
     *
     * @template T of object
     */
    private function newMetadataInstance(string $classname): ClassMetadata {
        return new ClassMetadata(
            $classname
        );
    }
}
