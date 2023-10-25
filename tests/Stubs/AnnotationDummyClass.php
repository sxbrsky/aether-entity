<?php

namespace Nulldark\Tests\Stubs;

use Nulldark\ORM\Mapping\Annotations;

#[Annotations\Entity]
class AnnotationDummyClass
{
    #[Annotations\Id]
    public int $id;

    #[Annotations\Column]
    public string $name;
}