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

namespace NullDark\Metadata\Reflection;

use ReflectionClass;
use ReflectionException;
use RuntimeException;

/**
 * @author Dominik Szamburski
 * @package Metadata
 * @subpackage Reflection
 * @license MIT
 * @version 0.1.0
 */
final class RuntimeReflection implements ReflectionInterface
{
    public function getParentClasses(string $class): array
    {
        if (!class_exists($class)) {
            throw new RuntimeException("class $class not exists.");
        }

        return class_parents($class);
    }

    public function getClassNamespace(string $class): string
    {
        return $this->getClass($class)->getNamespaceName();
    }

    /**
     * @throws ReflectionException
     */
    public function getClass(string $class): ReflectionClass
    {
        return new ReflectionClass($class);
    }
}