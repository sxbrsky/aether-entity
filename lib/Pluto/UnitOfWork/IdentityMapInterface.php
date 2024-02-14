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

use BackedEnum;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\UnitOfWork
 * @since 0.1.0
 */
interface IdentityMapInterface
{
    /**
     * Puts a entity into identity map.
     *
     * @param mixed   $identifier
     * @param object  $entity
     * @psalm-param T $entity
     *
     * @return bool
     *
     * @template T of object
     */
    public function put(mixed $identifier, object $entity): bool;

    /**
     * Gets entity from identity map.
     *
     * @param mixed                 $identifier
     * @param string                $classname
     * @psalm-param class-string<T> $classname
     *
     * @return T|false
     * @psalm-return T|false
     *
     * @template T
     */
    public function get(mixed $identifier, string $classname): mixed;

    /**
     * Compute id hash for given identifiers.
     *
     * @param mixed[] $identifier
     *
     * @return string
     */
    public function computeIdHash(array $identifier): string;
}
