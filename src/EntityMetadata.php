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

namespace NullDark;

use NullDark\Metadata\Reflection\ReflectionInterface;
use ReflectionClass;

/**
 * @author Dominik Szamburski
 * @package Metadata
 * @license MIT
 * @version 0.1.0
 */
final class EntityMetadata
{
    public array $table;
    public array $identifier = [];
    public array $fieldsMapping;
    public string $entityName;
    public string $rootEntityName;
    public string $reflNamespace;
    public ReflectionClass $reflClass;


    public function __construct(string $className)
    {
        $this->entityName = $className;
    }

    /**
     * @param ReflectionInterface $reflection
     * @return void
     */
    public function initializeReflection(ReflectionInterface $reflection): void
    {
        $this->reflClass = $reflection->getClass($this->entityName);
        $this->reflNamespace = $reflection->getClassNamespace($this->entityName);

        if ($this->reflClass) {
            $this->entityName = $this->reflClass->name;
            $this->rootEntityName = $this->reflClass->name;
        }
    }

    /**
     * @return ReflectionClass
     */
    public function getReflectionClass(): ReflectionClass
    {
        return $this->reflClass;
    }

    /**
     * @param array $table
     * @return void
     */
    public function setPrimaryTable(array $table): void
    {
        if (isset($table['name'])) {
            if (str_contains('.', $table['name'])) {
                [$this->table['name'], $table['name']] = explode('.', $table['name']);
            }

            $this->table['name'] = $table['name'];
        }

        if (isset($table['schema'])) {
            $this->table['schema'] = $table['schema'];
        }
    }

    /**
     * @param array $mapping
     * @return void
     */
    public function fieldMapping(array $mapping): void
    {
        if (!isset($mapping['type'])) {
            $mapping['type'] = 'string';
        }

        if (isset($mapping['id']) && $mapping['id'] === true) {
            if (!in_array($mapping['fieldName'], $this->identifier, true)) {
                $this->identifier[] = $mapping['fieldName'];
            }
        }

        $this->fieldsMapping[$mapping['fieldName']] = $mapping;
    }
}