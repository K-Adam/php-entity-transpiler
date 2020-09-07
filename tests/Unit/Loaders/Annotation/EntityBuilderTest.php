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

    public function testOverrideEnumValues() {
        $name = "MyNs\\MyEnum";

        $prophecy = $this->prophet->prophesize();
        $prophecy->willExtend(ReflectionClass::class);
        $prophecy->getName()->willReturn($name);
        $prophecy->getConstants()->willReturn([]);
        $dummyReflector = $prophecy->reveal();

        $overrideValues = new ET\OverrideEnumValues([
            "NAME1" => "VALUE1",
            "NAME2" => "VALUE2"
        ]);

        $prophecy = $this->prophet->prophesize();
        $prophecy->willExtend(AnnotationReader::class);
        $prophecy->getClassAnnotation($dummyReflector, ET\OverrideEnumValues::class)->willReturn($overrideValues);
        $dummyReader = $prophecy->reveal();

        $builder = new EntityBuilder($dummyReader);

        $enumAnnotation = new ET\Entity();
        $enumAnnotation->type = "ENUM";

        $entity = $builder->build($dummyReflector, $enumAnnotation);

        $this->assertEquals(2, count($entity->enumValues));

        $v0 = $entity->enumValues[0];
        $v1 = $entity->enumValues[1];

        $this->assertEquals("NAME1", $v0->name);
        $this->assertEquals("VALUE1", $v0->value);
        $this->assertEquals("NAME2", $v1->name);
        $this->assertEquals("VALUE2", $v1->value);
    }

}
