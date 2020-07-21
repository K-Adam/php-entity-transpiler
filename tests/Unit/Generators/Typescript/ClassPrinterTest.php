<?php

namespace Tests\Unit\Generators\Typescript;

use Tests\TestCase;

use EntityTranspiler\Generators\Typescript\ClassPrinter;
use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\Indentation;
use EntityTranspiler\Entity;
use EntityTranspiler\Properties\EnumValue;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Properties\PhpType;
use Tests\Utils\SimpleClassResolver;

class ClassPrinterTest extends TestCase {

    private $printer;
    private $prophet;

    protected function setUp(): void {
        $this->printer = new ClassPrinter(
            new SimpleClassResolver(),
            new Indentation(Indentation::TYPE_SPACE, 2)
        );
        $this->prophet = new \Prophecy\Prophet;
    }

    public function testTransformedClassName() {
        $transformer = new class extends Transformer {
            public function transform(ClassRef $ref): ClassRef {
                return new ClassRef("TestClass");
            }
        };
        $this->printer->transformer = $transformer;

        $this->assertEquals("TestClass", $this->printer->getClassName( new ClassRef("MyClass") ));
    }

    public function testEmptyEnum() {
        $entity = new Entity("MyEntity");
        $entity->type = Entity::TYPE_ENUM;

        $this->assertEquals("enum MyEntity {\n}", $this->printer->getClassString($entity));
    }

    /** @depends testEmptyEnum */
    public function testEnum() {
        $entity = new Entity("MyEntity");
        $entity->type = Entity::TYPE_ENUM;

        $entity->enumValues[] = new EnumValue("foo", "FOO");
        $entity->enumValues[] = new EnumValue("bar", 2);

        $this->assertEquals("enum MyEntity {\n  foo=\"FOO\",\n  bar=2\n}", $this->printer->getClassString($entity));
    }

    /** @depends testEnum */
    public function testEnumFormat() {
        $entity = new Entity("MyEntity");
        $entity->type = Entity::TYPE_ENUM;

        $entity->enumValues[] = new EnumValue("FOO", "BAR");

        $this->printer->enumNameFormat = Writer::PASCAL_CASE;

        $this->assertEquals("enum MyEntity {\n  Foo=\"BAR\"\n}", $this->printer->getClassString($entity));
    }

    public function testAlias() {
        $entity = new Entity("MyEntity");
        $entity->type = Entity::TYPE_ALIAS;
        $entity->alias = new PhpType(PhpType::TYPE_SCALAR, "int");

        $this->assertEquals("type MyEntity = number", $this->printer->getClassString($entity));
    }

    public function testHeaderWithParentClass() {

        $entity = new Entity("MyClass");
        $entity->parentClass = new ClassRef("BaseClass");

        $this->assertEquals("class MyClass extends BaseClass", $this->printer->getClassHeader($entity));

    }

}
