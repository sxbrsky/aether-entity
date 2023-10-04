<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of EntityManager
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace NullDark\Metadata;

use NullDark\Mapping\Attribute;
use ReflectionAttribute;
use ReflectionClass;

/**
 * @author Dominik Szamburski
 * @package Metadata
 * @license MIT
 * @version 0.1.0
 */
final class AttributeReader
{
    /**
     * @param ReflectionClass $reflectionClass
     * @return Attribute[]
     */
    public function getClassAttributes(ReflectionClass $reflectionClass): array
    {
        return $this->convertAttributesToInstances(
            $reflectionClass->getAttributes()
        );
    }

    /**
     * @param ReflectionAttribute[] $attributes
     * @return Attribute[]
     */
    private function convertAttributesToInstances(array $attributes): array
    {
        $instances = [];

        foreach ($attributes as $attribute) {
            $name = $attribute->getName();
            if (!is_subclass_of($name, Attribute::class)) {
                continue;
            }

            $instances[$name] = $attribute->newInstance();
        }

        return $instances;
    }

}