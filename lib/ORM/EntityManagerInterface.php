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

namespace Nulldark\ORM;

use Nulldark\DBAL\ConnectionInterface;
use Nulldark\DBAL\Query\QueryBuilderInterface;
use Nulldark\ORM\Mapping\Metadata;
use Nulldark\ORM\Repository\EntityRepository;
use Nulldark\ORM\UnitOfWork\UnitOfWorkInterface;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM
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
