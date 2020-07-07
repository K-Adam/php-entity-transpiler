<?php

namespace Tests\Unit\Utils\ClassRef\Transformer;

use Tests\TestCase;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ClassRef\Transformer\Flatten;

class FlattenTest extends TestCase {
    
    public function testSimple() {
        $transformer = new Flatten();
        
        $resultFullName = $transformer->transform(new ClassRef("MyNamespace\\MyClass"))->getFullName();
        
        $this->assertEquals("MyClass", $resultFullName);
    }
    
}
