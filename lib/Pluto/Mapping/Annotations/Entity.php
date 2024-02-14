<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Mapping\Annotations;

use Attribute;
use Pluto\Repository\EntityRepository;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Mapping\Annotations
 * @since 0.1.0
 *
 * @template T of object
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class Entity implements Annotation
{
    /** @psalm-param class-string<EntityRepository<T>>|null $repositoryClass */
    public function __construct(
        public readonly string|null $repositoryClass = null
    ) {
    }
}
