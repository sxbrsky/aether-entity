<?php

namespace Nulldark\Tests\Units\Mapping;

use Nulldark\ORM\Mapping\Annotations as ORM;
use Nulldark\ORM\Mapping\Metadata;
use Nulldark\Tests\Stubs\AnnotationDummyClass;
use Nulldark\Tests\Stubs\DummyClass;
use Nulldark\Tests\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use function PHPUnit\Framework\assertNotEmpty;

#[CoversClass(Metadata::class)]
class MetadataTest extends TestCase
{
    public function testSetPrimaryTable(): void
    {
        $table = new ORM\Table('foo');

        $class = new Metadata(AnnotationDummyClass::class);
        $class->setPrimaryTable($table);

        self::assertEquals($table->name, $class->table['name']);
        self::assertNull($class->table['schema']);
    }

    public function testSetFieldMapping(): void
    {
        $column = new ORM\Column();
        $property = new \ReflectionProperty(AnnotationDummyClass::class, 'name');

        $class = new Metadata(AnnotationDummyClass::class);
        $class->setFieldMapping($property, $column);

        self::assertNotEmpty($class->fieldMappings);
        self::assertEquals($property->name, $class->fieldMappings[$property->name]['fieldName']);

    }
}