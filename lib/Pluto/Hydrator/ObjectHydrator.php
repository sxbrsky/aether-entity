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

use Pluto\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Hydrator
 * @since 0.1.0
 */
class ObjectHydrator implements HydratorInterface
{
    public function __construct(
        private readonly Metadata $class
    ) {
    }

    /**
     * @inheritDoc
     */
    public function hydrate(array $data, object $entity): object {
        foreach ($data as $field => $value) {
            if (isset($this->class->fieldMappings[$field])) {
                if ($this->class->properties[$field] === null) {
                    continue;
                }

                $this->class->properties[$field]->setValue($entity, $value);
            }
        }

        return $entity;
    }
}
