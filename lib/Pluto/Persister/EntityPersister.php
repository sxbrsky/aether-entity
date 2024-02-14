<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Persister;

use Nulldark\DBAL\Query\QueryBuilderInterface;
use Pluto\EntityManagerInterface;
use Pluto\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Persister
 * @since 0.1.0
 */
final class EntityPersister extends AbstractPersister
{
    /**
     * @inheritDoc
     */
    public function load(array $criteria, $entity = null): ?object
    {
        $query = $this->getSelectQuery($criteria);

        $record = $query->get()->first();

        if ($record === null) {
            return null;
        }

        if ($entity === null) {
            $entity = $this->class->newInstance();
        }

        $hydrator = $this->getEntityHydrator();
        $hydrator->hydrate((array) $record, $entity);

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function loadAll(array $criteria): array
    {
        $hydrator = $this->getEntityHydrator();
        $query = $this->getSelectQuery($criteria);

        $entities = [];

        foreach ($query->get() as $record) {
            $entity = $this->class->newInstance();
            $entities[] = $hydrator->hydrate((array) $record, $entity);
        }

        return $entities;
    }

    private function getSelectQuery(array $criteria): QueryBuilderInterface
    {
        $qb = $this->em->createQueryBuilder()
            ->select('*')
            ->from($this->class->table['name']);

        foreach ($criteria as $column => $value) {
            $qb->where(
                column: $column,
                operator:'=',
                values: is_numeric($value) ? $value : "'$value'"
            );
        }

        return $qb;
    }
}
