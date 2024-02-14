<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Hydrator;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Hydrator
 * @since 0.1.0
 */
interface HydratorInterface
{
    /**
     * Hydrate $object with the provided $data.
     *
     * @param array<array-key, mixed> $data
     * @param object                  $entity
     * @psalm-param T                 $entity
     *
     * @return object
     * @psalm-return T
     *
     * @template T of object
     */
    public function hydrate(array $data, object $entity): object;
}
