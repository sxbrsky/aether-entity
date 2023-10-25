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

namespace Nulldark\ORM\Hydrator;

use Nulldark\ORM\Mapping\Metadata;

/**
 * @author Dominik Szamburski
 * @license MIT
 * @package Nulldark\ORM\Hydrator
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
    public function hydrate(array $data, object|null $entity = null): object
    {
        if ($entity === null) {
            $entity = $this->class->newInstance();
        }

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
