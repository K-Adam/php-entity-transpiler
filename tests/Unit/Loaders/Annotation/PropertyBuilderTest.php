<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Loaders\Annotation\PropertyBuilder;

use EntityTranspiler\Annotations as ET;
use EntityTranspiler\Properties\PhpType;

class PropertyBuilderTest extends TestCase {

    private $builder;

    protected function setUp(): void {
        $this->builder = new PropertyBuilder();
    }

    public function testSimple() {
        $annotation = new ET\Property();

        $property = $this->builder->build("pName", $annotation);

        $this->assertEquals("pName", $property->name);
        $this->assertEquals(PhpType::TYPE_MIXED, $property->type->type);
    }

    public function testCollection() {
        $annotation = new ET\Property();
        $annotation->type = new ET\Collection();

        $property = $this->builder->build("pName", $annotation);

        $this->assertEquals("pName", $property->name);
        $this->assertEquals(PhpType::TYPE_ARRAY, $property->type->type);
        $this->assertEquals(PhpType::TYPE_MIXED, $property->type->value->type);
    }

    public function testDefault() {
        $annotation = new ET\Property();
        $annotation->default = "test";

        $property = $this->builder->build("pName", $annotation);

        $this->assertEquals("test", $property->default);
    }

    public function testOptional() {
        $annotation1 = new ET\Property();
        $annotation1->optional = true;

        $annotation2 = new ET\Property();
        $annotation2->optional = false;

        $property1 = $this->builder->build("pName", $annotation1);
        $property2 = $this->builder->build("pName", $annotation2);

        $this->assertTrue($property1->optional);
        $this->assertFalse($property2->optional);
    }

    public function testNullable() {
        $annotation1 = new ET\Property();
        $annotation1->nullable = true;

        $annotation2 = new ET\Property();
        $annotation2->nullable = false;

        $property1 = $this->builder->build("pName", $annotation1);
        $property2 = $this->builder->build("pName", $annotation2);

        $this->assertTrue($property1->nullable);
        $this->assertFalse($property2->nullable);
    }

}
