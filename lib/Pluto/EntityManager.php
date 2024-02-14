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
use Pluto\Mapping\MetadataFactory;
use Pluto\Repository\EntityRepository;
use Pluto\UnitOfWork\UnitOfWork;
use Pluto\UnitOfWork\UnitOfWorkInterface;

/**
 * @final
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto
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
    public function find(string $classname, mixed $id): ?object {
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

    public function getMedata(string $classname): Metadata {
        return $this->metadataFactory->getMedataFor($classname);
    }

    /**
     * @inheritDoc
     */
    public function getUnitOfWork(): UnitOfWorkInterface {
        return $this->unitOfWork;
    }

    /**
     * @inheritDoc
     */
    public function getConnection(): ConnectionInterface {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(string $classname): EntityRepository {
        $class = $this->getMedata($classname);

        $repository = $class->customRepositoryClassname === null
            ? EntityRepository::class
            : $class->customRepositoryClassname;

        return new $repository($this, $class);
    }

    /**
     * @inheritDoc
     */
    public function createQueryBuilder(): QueryBuilderInterface {
        return $this->getConnection()->getQueryBuilder();
    }
}
