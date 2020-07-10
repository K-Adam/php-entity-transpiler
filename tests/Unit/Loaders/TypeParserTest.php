<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Loaders\TypeParser;
use EntityTranspiler\Properties\PhpType;

class TypeParserTest extends TestCase {

    private $parser;

    protected function setUp(): void {
        $this->parser = new TypeParser();
    }

    public function testScalars() {
        $intType = $this->parser->parse("int");
        $stringType = $this->parser->parse("string");
        $floatType = $this->parser->parse("float");

        $this->assertEquals(PhpType::TYPE_SCALAR, $intType->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $stringType->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $floatType->type);

        $this->assertEquals("int", $intType->value);
        $this->assertEquals("string", $stringType->value);
        $this->assertEquals("float", $floatType->value);
    }

    /**
     * @depends testScalars
     */
    public function testArray() {

        $intArrayType = $this->parser->parse("array<int>");

        $this->assertEquals(PhpType::TYPE_ARRAY, $intArrayType->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $intArrayType->value->type);
        $this->assertEquals("int", $intArrayType->value->value);

    }

    /**
     * @depends testArray
     */
    public function testNestedArray() {
        $nestedIntArrayType = $this->parser->parse("array<array<int>>");

        $this->assertEquals(PhpType::TYPE_ARRAY, $nestedIntArrayType->value->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $nestedIntArrayType->value->value->type);
    }

    /**
     * @depends testArray
     */
    public function testAnyArray() {

        $intArrayType = $this->parser->parse("array");

        $this->assertEquals(PhpType::TYPE_ARRAY, $intArrayType->type);
        $this->assertEquals(PhpType::TYPE_MIXED, $intArrayType->value->type);

    }

    /**
     * @depends testArray
     */
    public function testArrayBraces() {

        $intArrayType = $this->parser->parse("int[]");

        $this->assertEquals(PhpType::TYPE_ARRAY, $intArrayType->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $intArrayType->value->type);
        $this->assertEquals("int", $intArrayType->value->value);

    }

    /**
     * @depends testArrayBraces
     */
    public function testNestedArrayBraces() {
        $nestedIntArrayType = $this->parser->parse("int[][]");

        $this->assertEquals(PhpType::TYPE_ARRAY, $nestedIntArrayType->value->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $nestedIntArrayType->value->value->type);
    }

    /**
     * @depends testScalars
     */
    public function testClass() {
        $dtType = $this->parser->parse("DateTime");

        $this->assertEquals(PhpType::TYPE_CLASS, $dtType->type);
        $this->assertEquals("DateTime", $dtType->value);
    }

    /**
     * @depends testClass
     */
    public function testUserClass() {
        $cName = "MyNamespace\\MyClass";
        $uType = $this->parser->parse($cName);

        $this->assertEquals(PhpType::TYPE_CLASS, $uType->type);
        $this->assertEquals($cName, $uType->value);
    }

    /**
     * @depends testScalars
     */
    public function testObject() {

        $intObjectType = $this->parser->parse("{int}");

        $this->assertEquals(PhpType::TYPE_OBJECT, $intObjectType->type);
        $this->assertEquals("string", $intObjectType->key);
        $this->assertEquals(PhpType::TYPE_SCALAR, $intObjectType->value->type);
        $this->assertEquals("int", $intObjectType->value->value);

    }

    /**
     * @depends testObject
     */
    public function testObjectWithKey() {

        $strObjectType = $this->parser->parse("{int:string}");

        $this->assertEquals(PhpType::TYPE_OBJECT, $strObjectType->type);
        $this->assertEquals("int", $strObjectType->key);
        $this->assertEquals(PhpType::TYPE_SCALAR, $strObjectType->value->type);
        $this->assertEquals("string", $strObjectType->value->value);

    }

    /**
     * @depends testObjectWithKey
     */
    public function testNestedObject() {
        $nestedObjType = $this->parser->parse("{int:{int:string}}");

        $this->assertEquals(PhpType::TYPE_OBJECT, $nestedObjType->value->type);
        $this->assertEquals(PhpType::TYPE_SCALAR, $nestedObjType->value->value->type);
        $this->assertEquals("string", $nestedObjType->value->value->value);
    }

    /**
     * @depends testObjectWithKey
     */
    public function testObjectWithKeyName() {

        $strObjectType = $this->parser->parse("{[id:int]:string}");

        $this->assertEquals(PhpType::TYPE_OBJECT, $strObjectType->type);
        $this->assertEquals("int", $strObjectType->key);
        $this->assertEquals("id", $strObjectType->keyName);

    }

}
