<?php

/**
 * Copyright (c) 2023 Dominik Szamburski
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Nulldark\ORM\UnitOfWork;

use BackedEnum;
use Nulldark\Stdlib\Collections\Map\HashMap;
use Nulldark\Stdlib\Collections\Map\MapInterface;

/**
 * @internal
 *
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\UnitOfWork
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
