<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of EntityManager
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace NullDark\Metadata;


use NullDark\Mapping;
use NullDark\Metadata\Reflection\RuntimeReflection;

/**
 * @author Dominik Szamburski
 * @package Metadata
 * @license MIT
 * @version 0.1.0
 */
final class ClassMetadataFactory
{
    /** @var array<string, ClassMetadata> $loadedMetadata */
    private array $loadedMetadata;

    /** @var AttributeReader $reader */
    private AttributeReader $reader;

    public function __construct()
    {
        $this->reader = new AttributeReader();
    }

    /**
     * @param string $className
     * @return ClassMetadata
     */
    public function loadClassMetadataFor(string $className): ClassMetadata
    {
        $className = $this->normalizeClassname($className);

        if (isset($this->loadedMetadata[$className])) {
            return $this->loadedMetadata[$className];
        }

        $this->loadMetadata($className);

        return $this->loadedMetadata[$className];
    }

    /**
     * @param string $className
     * @return string
     */
    private function normalizeClassname(string $className): string
    {
        return ltrim($className, '\\');
    }

    /**
     * @param string $class
     * @return ClassMetadata
     */
    private function newClassMetadataInstance(string $class): ClassMetadata
    {
        return new ClassMetadata(
            $class
        );
    }

    /**
     * @param string $className
     * @return void
     */
    private function loadMetadata(string $className): void
    {
        $reflService = new RuntimeReflection();

        $parentClasses = $reflService->getParentClasses($className);
        $parentClasses[] = $className;

        foreach ($parentClasses as $parentClass) {
            $class = $this->newClassMetadataInstance($parentClass);
            $class->initializeReflection($reflService);

            $this->doLoadMetadata($class);
            $this->loadedMetadata[$parentClass] = $class;
        }
    }

    /**
     * @param ClassMetadata $class
     * @return void
     */
    private function doLoadMetadata(ClassMetadata $class): void
    {
        $reflectionClass = $class->getReflectionClass();
        $classAttributes = $this->reader->getClassAttributes($reflectionClass);

        $primaryTable = [];
        if (isset($classAttributes[Mapping\Table::class])) {
            $table = $classAttributes[Mapping\Table::class];

            $primaryTable['name'] = $table->name;
            $primaryTable['schema'] = $table->schema;
        }

        $class->setPrimaryTable($primaryTable);
    }
}