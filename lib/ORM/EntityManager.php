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
use Nulldark\ORM\Mapping\MetadataFactory;
use Nulldark\ORM\Repository\EntityRepository;
use Nulldark\ORM\UnitOfWork\UnitOfWork;
use Nulldark\ORM\UnitOfWork\UnitOfWorkInterface;

/**
 * @final
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM
 * @since 0.1.0
 */
final class EntityManager implements EntityManagerInterface
{
    private UnitOfWorkInterface $unitOfWork;

    private MetadataFactory $metadataFactory;

    public function __construct(
        private readonly ConnectionInterface $connection
    ) {
        $this->unitOfWork = new UnitOfWork($this);

        $this->metadataFactory = new MetadataFactory();
    }

    /**
     * @inheritDoc
     */
    public function find(string $classname, mixed $id): ?object
    {
        if ($id === null) {
            return null;
        }

        $class = $this->metadataFactory->getMedataFor($classname);

        if (!is_array($id)) {
            $id = [$class->identifier[0] => $id];
        }

        $sortedId = [];

        foreach ($class->identifier as $identifier) {
            if (!isset($id[$identifier])) {
                break;
            }

            $sortedId[$identifier] = $id[$identifier] instanceof \BackedEnum
                ? $id[$identifier]->value
                : $id[$identifier];

            unset($id[$identifier]);
        }

        $unitOfWork = $this->getUnitOfWork();

        $entity = $unitOfWork->tryGetById($sortedId, $class->name);
        if ($entity !== false) {
            if (!($entity instanceof $class->name)) {
                return null;
            }

            return $entity;
        }

        $persister = $unitOfWork->getEntityPersister($class->name);
        $entity = $persister->load($sortedId);

        if ($entity !== null) {
            $unitOfWork->putToIdentityMap($sortedId, $entity);
        }

        return $entity;
    }

    public function getMedata(string $classname): Metadata
    {
        return $this->metadataFactory->getMedataFor($classname);
    }

    /**
     * @inheritDoc
     */
    public function getUnitOfWork(): UnitOfWorkInterface
    {
        return $this->unitOfWork;
    }

    /**
     * @inheritDoc
     */
    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $classname): EntityRepository
    {
        $class = $this->getMedata($classname);

        $repository = $class->customRepositoryClassname === null
            ? EntityRepository::class
            : $class->customRepositoryClassname;

        return new $repository($this, $class);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(): QueryBuilderInterface
    {
        return $this->getConnection()->getQueryBuilder();
    }
}
