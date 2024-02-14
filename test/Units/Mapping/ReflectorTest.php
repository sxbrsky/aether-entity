<?php

namespace Pluto\test\Units\Mapping;

use Pluto\Mapping\Annotations as ORM;
use Pluto\Mapping\Reflector;
use Pluto\test\Stubs\AnnotationDummyClass;
use Pluto\test\Stubs\DummyClass;
use Pluto\test\Stubs\DummyClassExtended;
use Pluto\test\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Reflector::class)]
class ReflectorTest extends TestCase
{
    /**
     * @dataProvider parentClassesProvider
     *
     * @param class-string $classname
     * @param array<class-string> $expected
     */
    public function testGetParentClasses(string $classname, array $expected): void {
        $reflector = new Reflector();

        self::assertEquals(
            $expected,
            $reflector->getParentClasses($classname)
        );
    }

    /**
     * Data provider for test method: testGetParentClasses
     *
     * [0]: Value given to the method
     * [1]: Expected value
     *
     * @return array<array<int, class-string>|array<int, array<string, string>|string>>
     */
    public static function parentClassesProvider(): iterable {
        yield [DummyClass::class, []];
        yield [DummyClassExtended::class, [DummyClass::class => DummyClass::class]];
    }

    public function testGetClassAnnotations(): void {
        $reflector = new Reflector();
        $reflectionClass = new \ReflectionClass(AnnotationDummyClass::class);

        $classAnnotations = $reflector->getClassAnnotations($reflectionClass);

        self::assertNotEmpty($classAnnotations);
        self::assertInstanceOf(ORM\Annotation::class, $classAnnotations[ORM\Entity::class]);
    }

    public function testGetPropertyAnnotations(): void {
        $reflector = new Reflector();
        $reflectionProperty = new \ReflectionProperty(AnnotationDummyClass::class, 'id');

        $propertyAnnotations = $reflector->getPropertyAnnotations($reflectionProperty);

        self::assertNotEmpty($propertyAnnotations);
        self::assertInstanceOf(ORM\Annotation::class, $propertyAnnotations[ORM\Id::class]);
        self::assertEquals(ORM\Id::class, $propertyAnnotations[ORM\Id::class]::class);
    }

    public function testGetPropertyAnnotation(): void {
        $reflector = new Reflector();
        $reflectionProperty = new \ReflectionProperty(AnnotationDummyClass::class, 'name');

        self::assertInstanceOf(
            ORM\Column::class,
            $reflector->getPropertyAnnotation($reflectionProperty, ORM\Column::class)
        );
    }
}
