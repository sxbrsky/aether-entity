<?php

/*
 * This file is part of the nuldark/pluto.
 *
 * Copyright (C) 2023-2024 Dominik Szamburski
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE.md file for details.
 */


namespace Pluto\UnitOfWork;

use BackedEnum;
use Nulldark\Stdlib\Collections\Map\HashMap;
use Nulldark\Stdlib\Collections\Map\MapInterface;

/**
 * @internal
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Pluto\UnitOfWork
 * @since 0.1.0
 */
class IdentityMap implements IdentityMapInterface
{
    /** @var array<array-key, MapInterface> $identityMap */
    private array $identityMap = [];

    /**
     * @inheritDoc
     */
    public function put(mixed $identifier, object $entity): bool
    {
        if (!isset($this->identityMap[$entity::class])) {
            $this->identityMap[$entity::class] = new HashMap();
        }

        $this->identityMap[$entity::class]->put($this->computeIdHash((array)$identifier), $entity);

        return true;
    }

    /**
     * @inheritDoc
     */
    public function get(mixed $identifier, string $classname): mixed
    {
        $idHash = $this->computeIdHash((array)$identifier);

        if (!isset($this->identityMap[$classname])) {
            return false;
        }

        return $this->identityMap[$classname][$idHash];
    }

    /**
     * @inheritDoc
     */
    public function computeIdHash(array $identifier): string
    {
        return implode(
            ' ',
            array_map(
                static function ($value) {
                    if ($value instanceof BackedEnum) {
                        return $value->value;
                    }

                    return $value;
                },
                $identifier,
            ),
        );
    }
}
