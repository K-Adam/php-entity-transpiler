<?php

namespace Tests\Unit\Generators\Typescript;

use Tests\TestCase;

use EntityTranspiler\Properties\Property;
use EntityTranspiler\Generators\Typescript\PropertyPrinter;
use EntityTranspiler\Generators\Typescript\TypeNameResolver;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Utils\NameFormat\Writer;
use Tests\Utils\SimpleClassResolver;

use EntityTranspiler\Properties\PhpType;

class PropertyPrinterTest extends TestCase {

    private $printer;

    protected function setUp(): void {
        $this->printer = new PropertyPrinter(
            new TypeNameResolver(
                new SimpleClassResolver()
            )
        );
    }

    public function testSimpleProperty() {
        $prop = new Property("testProp", new PhpType(PhpType::TYPE_MIXED));

        $expected = "testProp: any";
        $result = $this->printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

    public function testClassProperty() {
        $classResolver = ClassResolver::create([[
            "source" => "*",
            "classNameResolver" => ["format"=>Writer::CAMEL_CASE],
            "pathResolver" => ["type"=>"SINGLE_FILE", "path"=>"out"]
        ]]);
        $printer = new PropertyPrinter(new TypeNameResolver($classResolver));

        $prop = new Property("testInstance", new PhpType(PhpType::TYPE_CLASS, "App\\MyClass"));

        $expected = "testInstance: myClass";
        $result = $printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

    public function testOptional() {
        $prop = new Property("testProp", new PhpType(PhpType::TYPE_SCALAR, "string"));
        $prop->optional = true;

        $expected = "testProp?: string";
        $result = $this->printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

    public function testNullable() {
        $prop = new Property("testProp", new PhpType(PhpType::TYPE_SCALAR, "string"));
        $prop->nullable = true;

        $expected = "testProp: string|null";
        $result = $this->printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

    public function testDefaultString() {
        $prop = new Property("testProp", new PhpType(PhpType::TYPE_SCALAR, "string"));
        $prop->default = "defaultString";

        $expected = 'testProp: string = "defaultString"';
        $result = $this->printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

    public function testDefaultNumber() {
        $prop = new Property("testProp", new PhpType(PhpType::TYPE_SCALAR, "int"));
        $prop->default = 10;

        $expected = 'testProp: number = 10';
        $result = $this->printer->getPropertyString($prop);

        $this->assertEquals($expected, $result);
    }

}
