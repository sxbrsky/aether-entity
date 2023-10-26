<?php

namespace Nulldark\Tests\Units\UnitOfWork;

use Nulldark\ORM\UnitOfWork\IdentityMap;
use Nulldark\ORM\UnitOfWork\IdentityMapInterface;
use Nulldark\Tests\Stubs\State;
use Nulldark\Tests\Stubs\User;
use Nulldark\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(IdentityMap::class)]
class IdentityMapTest extends TestCase
{
    private IdentityMapInterface $identityMap;

    public function setUp(): void
    {
        $this->identityMap = new IdentityMap();
    }

    public function testSet(): void
    {
        $this->identityMap->put(1, new User());
    }

    public function testComputeIdHashWithPrimitiveValue(): void
    {
        self::assertEquals(
            '1',
            $this->identityMap->computeIdHash([1])
        );
    }

    public function testComputeIdHashWithBackedEnum(): void
    {
        self::assertEquals(
            'foo',
            $this->identityMap->computeIdHash([State::FOO])
        );
    }
}
