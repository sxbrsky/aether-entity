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

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Mapping\Annotations
 * @since 0.1.0
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Id implements Annotation
{
}
