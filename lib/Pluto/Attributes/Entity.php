<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Pluto\Attributes;

use Attribute;
use Pluto\EntityRepository;

/**
 * @template T of object
 */
#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Entity implements MappingAttribute
{
    /** @param class-string<EntityRepository<T>>|null $repositoryClass */
    public function __construct(
        public string|null $repositoryClass = null
    ) {
    }
}
