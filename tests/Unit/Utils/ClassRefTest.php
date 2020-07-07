<?php

namespace Tests\Unit\Utils;

use Tests\TestCase;
use EntityTranspiler\Utils\ClassRef;

class ClassRefTest extends TestCase {

    public function testConstruction() {

        $fullName = "TestClasses\SimpleProject01\MyClass01";
        $ref = new ClassRef($fullName);

        $this->assertEquals($fullName, $ref->getFullName());
        $this->assertEquals("MyClass01", $ref->getName());
        $this->assertEquals(["TestClasses", "SimpleProject01"], $ref->getNamespaceChain());

    }

    public function testFromParsed() {

        $fullName = "TestClasses\SimpleProject01\MyClass01";
        $refExpected = new ClassRef($fullName);

        $ref = ClassRef::fromParsed(["TestClasses", "SimpleProject01"], "MyClass01");

        $this->assertEquals($refExpected, $ref);

    }

    public function testEquals() {

        $fullName = "TestClasses\MyClass";
        $ref1 = new ClassRef($fullName);
        $ref2 = new ClassRef($fullName);

        $this->assertEquals($ref1, $ref2);
    }

    public function testBuiltIn() {
        $this->assertFalse((new ClassRef("MyClass"))->isBuiltIn());
        $this->assertTrue((new ClassRef("DateTime"))->isBuiltIn());
    }

}
