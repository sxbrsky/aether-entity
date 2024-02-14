<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto;

use Nulldark\DBAL\ConnectionInterface;
use Nulldark\DBAL\Query\QueryBuilderInterface;
use Pluto\Mapping\Metadata;
use Pluto\Repository\EntityRepository;
use Pluto\UnitOfWork\UnitOfWorkInterface;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto
 * @since 0.1.0
 */
interface EntityManagerInterface
{
    /**
     * Finds an entity by its identifier.
     *
     * @param string                $classname  The class name of object to find.
     * @param mixed                 $id         The identity of entity to find.
     * @psalm-param class-string<T> $classname
     *
     * @return object|null The entity instance or `NULL` if not found.
     * @psalm-return T|null
     *
     * @template T of object
     */
    public function find(string $classname, mixed $id): ?object;

    /**
     * Returns a Metadata instance for given entity.
     *
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return Metadata
     *
     * @template T of object
     */
    public function getMedata(string $classname): Metadata;

    /**
     * Returns a Unit of Work instance.
     *
     * @return UnitOfWorkInterface
     */
    public function getUnitOfWork(): UnitOfWorkInterface;

    /**
     * Returns a connection.
     *
     * @return ConnectionInterface
     */
    public function getConnection(): ConnectionInterface;

    /**
     * Gets repository for the class.
     *
     * @psalm-param class-string<T> $classname
     * @psalm-return EntityRepository<T>
     *
     * @template T of object
     */
    public function getRepository(string $classname): EntityRepository;

    /**
     * Returns a query builder instance.
     *
     * @return QueryBuilderInterface
     */
    public function createQueryBuilder(): QueryBuilderInterface;
}
