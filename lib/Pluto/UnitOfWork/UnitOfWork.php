<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\UnitOfWork;

use Pluto\EntityManagerInterface;
use Pluto\Persister\EntityPersister;

/**
 * @internal
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\UnitOfWork
 * @since 0.1.0
 */
final class UnitOfWork implements UnitOfWorkInterface
{
    private IdentityMapInterface $identityMap;

    /** @var array<string, EntityPersister> $persisters */
    private array $persisters = [];
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
        $this->identityMap = new IdentityMap();
    }

    /**
     * @inheritDoc
     */
    public function tryGetById(mixed $id, string $classname): object|false {
        $entity = $this->identityMap->get($id, $classname);

        return $entity !== false
            ? $entity
            : false;
    }

    /**
     * @inheritDoc
     */
    public function putToIdentityMap(mixed $id, object $entity): bool {
        return $this->identityMap->put($id, $entity);
    }

    /**
     * @inheritDoc
     */
    public function getEntityPersister(string $classname): EntityPersister {
        if (isset($this->persisters[$classname])) {
            return $this->persisters[$classname];
        }

        return $this->persisters[$classname] = new EntityPersister(
            $this->em,
            $this->em->getMedata($classname)
        );
    }
}
