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

use Pluto\Mapping\Annotations as ORM;
use Pluto\Repository\EntityRepository;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * @psalm-type FieldMapping = array{
 *       type: string|null,
 *       fieldName: string|null,
 *       length?: int|null,
 *       id?: bool|null,
 *       unique?: bool|null,
 *       nullable?: bool|null,
 * }
 *
 *  @psalm-type TableMapping = array{
 *     name: string,
 *     schema: string|null
 *  }
 *
 * @template T of object
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Mapping
 * @since 0.1.0
 */
class Metadata
{
    /**
     * @psalm-var class-string
     */
    public string $name;

    /**
     * @psalm-var ?class-string<EntityRepository>
     */
    public string|null $customRepositoryClassname = null;

    /**
     * The primary table definition.
     *
     * @var array
     * @psalm-var TableMapping
     */
    public array $table;

    /**
     * @var ReflectionClass<T>|null $reflection
     */
    public ?ReflectionClass $reflection;

    /**
     * @var array<string, ReflectionProperty|null> $properties
     */
    public array $properties = [];

    /** @var list<string> $identifier */
    public array $identifier = [];

    /** @var array<string, FieldMapping> $fieldMappings */
    public array $fieldMappings = [];

    /**
     * @param class-string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Sets primary table definition based on given $table Annotation.
     *
     * @param ?ORM\Table $table
     * @return void
     */
    public function setPrimaryTable(?ORM\Table $table = null): void
    {
        if ($table === null) {
            return;
        }

        $this->table['name'] = $table->name;
        $this->table['schema'] = $table->schema;
    }

    /**
     * Sets field definition based on given arguments.
     *
     * @param ReflectionProperty $property
     * @param ?ORM\Column        $column
     * @param ?ORM\Id            $id
     *
     * @return void
     */
    public function setFieldMapping(\ReflectionProperty $property, ORM\Column $column = null, ORM\Id $id = null): void
    {
        if ($column === null) {
            return;
        }

        if (isset($this->fieldMappings[$property->name])) {
            return;
        }

        $mapping = [
          'fieldName' => $property->name,
          'type' => $column->type,
          'length' => $column->length,
          'nullable' => $column->nullable,
          'unique' => $column->unique
        ];

        if ($id !== null) {
            $mapping['id'] = true;
        }

        $this->fieldMappings[$property->name] = $mapping;

        if (isset($mapping['id'])) {
            if (! in_array($mapping['fieldName'], $this->identifier, true)) {
                $this->identifier[] = $mapping['fieldName'];
            }
        }
    }

    /**
     * Registers custom repository class for entity.
     *
     * @param string|null $classname
     * @psalm-param class-string<EntityRepository>|null $classname
     * @return void
     */
    public function setCustomRepository(?string $classname): void
    {
        $this->customRepositoryClassname = $classname;
    }

    /**
     * Initialize new class instance.
     *
     * @return object
     * @psalm-return object
     *
     * @throws ReflectionException
     */
    public function newInstance(): object
    {
        return (new ReflectionClass($this->name))->newInstanceWithoutConstructor();
    }
}
