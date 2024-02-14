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

use Pluto\Hydrator\HydratorInterface;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Persister
 * @since 0.1.0
 */
interface PersisterInterface
{
    /**
     * Gets an entity by a list of field criteria.
     *
     * @param array<string, mixed>  $criteria
     * @param object|null           $entity
     * @psalm-param object|null          $entity
     *
     * @return object|null
     */
    public function load(array $criteria, object $entity = null): ?object;

    /**
     * Gets a list of entities by a list of field criteria.
     *
     * @param array<string, mixed> $criteria
     *
     * @return object[]
     * @psalm-return list<object>
     */
    public function loadAll(array $criteria): array;

    /**
     * Returns a hydrator instance.
     *
     * @return HydratorInterface
     */
    public function getEntityHydrator(): HydratorInterface;
}
