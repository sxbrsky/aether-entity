<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Repository;

use Pluto\EntityManagerInterface;
use Pluto\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Persister
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
    public function find(mixed $id)
    {
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
