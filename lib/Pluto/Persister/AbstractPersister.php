<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\Persister;

use Pluto\EntityManagerInterface;
use Pluto\Hydrator\HydratorInterface;
use Pluto\Hydrator\ObjectHydrator;
use Pluto\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\Persister
 * @since 0.1.0
 */
abstract class AbstractPersister implements PersisterInterface
{
    protected HydratorInterface|null $entityHydrator = null;

    public function __construct(
        protected readonly EntityManagerInterface $em,
        protected readonly Metadata $class
    ) {
    }
    /**
     * @inheritDoc
     */
    public function getEntityHydrator(): HydratorInterface
    {
        if ($this->entityHydrator === null) {
            $this->entityHydrator = new ObjectHydrator($this->class);
        }

        return $this->entityHydrator;
    }
}
