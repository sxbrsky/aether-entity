<?php

namespace Pluto\Tests\Stubs;

use Pluto\Mapping\Annotations;

#[Annotations\Entity]
class AnnotationDummyClass
{
    #[Annotations\Id]
    public int $id;

    #[Annotations\Column]
    public string $name;
}
