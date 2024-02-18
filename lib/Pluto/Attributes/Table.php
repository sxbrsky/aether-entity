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

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Table implements MappingAttribute
{
    public function __construct(
        public string  $name,
        public ?string $schema = null
    ) {
    }
}
