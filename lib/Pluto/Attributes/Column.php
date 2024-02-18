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

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Column implements MappingAttribute
{
    public function __construct(
        public ?string $name = null,
        public ?string $type = null,
        public ?int $length = null,
        public ?bool $unique = false,
        public ?bool $nullable = false
    ) {
    }
}
