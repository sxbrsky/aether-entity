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

use Pluto\Persister\PersisterInterface;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\UnitOfWork
 * @since 0.1.0
 */
interface UnitOfWorkInterface
{
    /**
     * Tries get an entity from identity map.
     *
     * @param mixed                 $id
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return object|false
     * @psalm-return T|false
     *
     * @template T of object
     */
    public function tryGetById(mixed $id, string $classname): object|false;

    /**
     * Puts an object into identity map.
     *
     * @param mixed $id
     * @param object $entity
     * @psalm-param T $entity
     *
     * @return bool
     *
     * @template T of object
     */
    public function putToIdentityMap(mixed $id, object $entity): bool;

    /**
     * Gets a persister instance for given entity.
     *
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return PersisterInterface
     *
     * @template T of object
     */
    public function getEntityPersister(string $classname): PersisterInterface;
}
