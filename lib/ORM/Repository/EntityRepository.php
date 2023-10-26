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

namespace Nulldark\ORM\Repository;

use Nulldark\ORM\EntityManagerInterface;
use Nulldark\ORM\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\Persister
 * @since 0.1.0
 *
 * @template T of object
 */
class EntityRepository
{
    protected string $entityName;

    public function __construct(
        protected EntityManagerInterface $em,
        protected Metadata $class
    ) {
        $this->entityName = $class->name;
    }

    /**
     * Finds entity by its identifier.
     *
     * @param mixed $id
     *
     * @return object|null
     * @psalm-return T|null
     */
    public function find(mixed $id) {
        return $this->em->find($this->entityName, $id);
    }

    /**
     * Finds all entities in the repository.
     *
     * @return object[]
     * @psalm-return list<T>
     */
    public function findAll(): array
    {
        return $this->findBy([]);
    }

    /**
     * @param array<string, mixed> $criteria
     * @psalm-param array<string, mixed> $criteria
     *
     * @return object[]
     * @psalm-return list<T>
     */
    public function findBy(array $criteria): array
    {
        return $this->em->getUnitOfWork()
            ->getEntityPersister($this->entityName)
            ->loadAll($criteria);
    }
}