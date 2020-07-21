<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Entity;
use EntityTranspiler\Loaders\Annotation\EntityBuilder;
use EntityTranspiler\Properties\PhpType;
use EntityTranspiler\Annotations as ET;

use ReflectionClass;
use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationBuilderTest extends TestCase {

    private $builder;
    private $prophet;

    protected function setUp(): void {
        $this->builder = new EntityBuilder(new AnnotationReader());
        $this->prophet = new \Prophecy\Prophet;
    }

    public function testAlias() {
        $target = "MyNs\\MyClass";

        $aliasAnnotation = new ET\Entity();
        $aliasAnnotation->type = "ALIAS";
        $aliasAnnotation->target = $target;

        $prophecy = $this->prophet->prophesize();
        $prophecy->willExtend(ReflectionClass::class);
        $prophecy->getName()->willReturn($target);
        $dummyReflector = $prophecy->reveal();

        $entity = $this->builder->build($dummyReflector, $aliasAnnotation);

        $this->assertEquals(Entity::TYPE_ALIAS, $entity->type);
        $this->assertEquals(new PhpType(PhpType::TYPE_CLASS, $target), $entity->alias);
    }

}
