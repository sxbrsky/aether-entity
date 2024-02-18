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

use Pluto\EntityRepository;

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
 *     schema: string|null,
 *  }
 *
 * @template T of object
 */
class ClassMetadata
{
    /** @var null|class-string<EntityRepository> $customRepositoryClassname*/
    public string|null $customRepositoryClassname = null;

    /**
     * The primary table definition.
     *
     * @var null|TableMapping $table
     */
    public ?array $table = null;

    /** @var null|\ReflectionClass<T> $reflection */
    public ?\ReflectionClass $reflection;

    /** @var array<string, null|\ReflectionProperty> $properties */
    public array $properties = [];

    /** @var string[]|int[] $identifier */
    public array $identifier = [];

    /** @var array<string, FieldMapping> $fieldMappings */
    public array $fieldMappings = [];

    /** @param class-string<T> $name */
    public function __construct(
        /** @param class-string<T> $name */
        public string $name
    ) {
    }

    /**
     * Sets primary table definition based on given $table attribute.
     *
     * @param null|\Pluto\Attributes\Table $table
     *  The table attribute.
     *
     * @return void
     */
    public function setPrimaryTable(?\Pluto\Attributes\Table $table = null): void {
        if ($table === null) {
            return;
        }

        $this->table['name'] = $table->name;
        $this->table['schema'] = $table->schema;
    }

    /**
     * Sets field definition based on given arguments.
     *
     * @param \ReflectionProperty $property
     *  The reflection property instance.
     * @param null|\Pluto\Attributes\Column $column
     *  The column attribute.
     * @param null|\Pluto\Attributes\Id $id
     *  The id attribute.
     *
     * @return void
     */
    public function setFieldMapping(
        \ReflectionProperty $property,
        \Pluto\Attributes\Column $column = null,
        \Pluto\Attributes\Id $id = null
    ): void {
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
            if (!\in_array($mapping['fieldName'], $this->identifier, true)) {
                $this->identifier[] = $mapping['fieldName'];
            }
        }
    }

    /**
     * Registers custom repository class for entity.
     *
     * @param null|class-string<EntityRepository> $classname
     *  The class name of custom entity repository.
     *
     * @return void
     */
    public function setCustomRepository(?string $classname): void {
        $this->customRepositoryClassname = $classname;
    }

    /**
     * Initialize new class instance.
     *
     * @return T
     *  Returns a new entity instance.
     *
     * @throws \ReflectionException
     */
    public function newInstance(): object {
        return (new \ReflectionClass($this->name))->newInstanceWithoutConstructor();
    }
}
