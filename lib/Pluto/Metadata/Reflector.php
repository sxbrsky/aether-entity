<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */

namespace Pluto\Metadata;

use Pluto\Attributes\MappingAttribute;

final class Reflector
{
    /**
     * Gets a parent classes for given class.
     *
     * @param class-string<T> $classname
     *  The name of the class.
     *
     * @return array|string[]
     *  Returns the parent classes.
     *
     * @template T of object
     */
    public function getParentClasses(string $classname): array {
        $parents = \class_parents($classname);
        return $parents === false ? [] : $parents;
    }

    /**
     * Gets a class attributes.
     *
     * @param \ReflectionClass $class
     *  The reflection instance.
     *
     * @return array<class-string<\Pluto\Attributes\MappingAttribute>, \Pluto\Attributes\MappingAttribute>
     *  Returns the class attributes.
     */
    public function getClassAttributes(\ReflectionClass $class): array {
        return $this->convertAttributesToInstance(
            $class->getAttributes()
        );
    }

    /**
     * Gets a method attributes.
     *
     * @param \ReflectionMethod $method
     *  The method reflection instance.
     *
     * @return array<class-string<\Pluto\Attributes\MappingAttribute>, \Pluto\Attributes\MappingAttribute>
     *  Returns the method attributes.
     */
    public function getMethodAttributes(\ReflectionMethod $method): array {
        return $this->convertAttributesToInstance(
            $method->getAttributes()
        );
    }

    /**
     * Gets a property attribute.
     *
     * @param \ReflectionProperty $property
     *  The property reflection instance.
     *
     * @return array<class-string<T>, T>
     *  Returns the property attributes.
     *
     * @template T of \Pluto\Attributes\MappingAttribute
     */
    public function getPropertyAttributes(\ReflectionProperty $property): array {
        return $this->convertAttributesToInstance(
            $property->getAttributes()
        );
    }

    /**
     * Gets single property attribute.
     *
     * @param \ReflectionProperty $property
     *  The reflection property.
     * @param class-string<T> $attribute
     *  The class annotation
     *
     * @return T|null
     *  Return the attribute instance, otherwise NULL.
     *
     * @template T of \Pluto\Attributes\MappingAttribute
     */
    public function getPropertyAttribute(\ReflectionProperty $property, string $attribute): ?MappingAttribute {
        return $this->getPropertyAttributes($property)[$attribute] ?? null;
    }

    /**
     * Converts given attributes to instance.
     *
     * @param \ReflectionAttribute[] $attributes
     *  The attributes to convert.
     *
     * @return array<class-string<\Pluto\Attributes\MappingAttribute>, \Pluto\Attributes\MappingAttribute>
     *  The attributes instance.
     */
    private function convertAttributesToInstance(array $attributes): array {
        $instances = [];

        foreach ($attributes as $attribute) {
            if (!\is_subclass_of($attribute->getName(), MappingAttribute::class)) {
                continue;
            }

            $instance = $attribute->newInstance();
            \assert($instance instanceof MappingAttribute);

            $instances[$attribute->getName()] = $instance;
        }

        return $instances;
    }
}
