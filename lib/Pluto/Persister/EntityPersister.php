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
