<?php

namespace Pluto\test\Units\UnitOfWork;

use Pluto\UnitOfWork\IdentityMap;
use Pluto\UnitOfWork\IdentityMapInterface;
use Pluto\test\Stubs\State;
use Pluto\test\Stubs\User;
use Pluto\test\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IdentityMap::class)]
class IdentityMapTest extends TestCase
{
    private IdentityMapInterface $identityMap;

    public function setUp(): void {
        $this->identityMap = new IdentityMap();
    }

    public function testSet(): void {
        $this->identityMap->put(1, new User());
    }

    public function testComputeIdHashWithPrimitiveValue(): void {
        self::assertEquals(
            '1',
            $this->identityMap->computeIdHash([1])
        );
    }

    public function testComputeIdHashWithBackedEnum(): void {
        self::assertEquals(
            'foo',
            $this->identityMap->computeIdHash([State::FOO])
        );
    }
}
