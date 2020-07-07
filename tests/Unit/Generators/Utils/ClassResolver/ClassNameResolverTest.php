<?php

namespace Tests\Unit\Generators\ClassResolver;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\NameFormat\Writer;

class ClassNameResolverTest extends TestCase {

    private $resolver;

    protected function setUp(): void {
        $this->resolver = new ClassNameResolver(Writer::PASCAL_CASE);
    }

    public function testSimple() {
        $this->assertEquals("MyClass", $this->resolver->resolve(new ClassRef("my_ns\\my_class")));
    }
    
    public function testCreate() {
        $format = Writer::PASCAL_CASE;
        $res = ClassNameResolver::create(["format"=>$format]);
        
        $this->assertEquals($format, $res->getFormat());
    }

}
