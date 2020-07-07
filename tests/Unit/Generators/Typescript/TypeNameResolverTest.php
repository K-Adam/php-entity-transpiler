<?php

namespace Tests\Unit\Generators\Typescript;

use Tests\TestCase;

use EntityTranspiler\Generators\Typescript\TypeNameResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Utils\NameFormat\Writer;

use EntityTranspiler\Properties\PhpType;

class TypeNameResolverTest extends TestCase {

    private $resolver;

    protected function setUp(): void {
        $this->resolver = new TypeNameResolver();
    }

    public function testBuiltIn() {

        $this->assertEquals("Date", $this->resolver->getTypeName(new PhpType(PhpType::TYPE_CLASS, "DateTime")));

    }

    public function testObject() {

        $objType = new PhpType(PhpType::TYPE_OBJECT, new PhpType(PhpType::TYPE_SCALAR, "string"), "int");

        $this->assertEquals("{[key:number]:string}", $this->resolver->getTypeName($objType));

    }

}
