<?php

namespace Tests\Unit\Utils\ClassRef\Transformer;

use Tests\TestCase;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ClassRef\Transformer\SliceNamespace;

class SliceNamespaceTest extends TestCase{
    
    public function testWithoutLength() {
        $transformer = new SliceNamespace(2);
        
        $resultFullName = $transformer->transform(new ClassRef("A\\B\\C\\D\\MyClass"))->getFullName();
        
        $this->assertEquals("C\\D\\MyClass", $resultFullName);
    }
    
    public function testWithLength() {
        $transformer = new SliceNamespace(2, 3);
        
        $resultFullName = $transformer->transform(new ClassRef("A\\B\\C\\D\\E\\F\\G\\MyClass"))->getFullName();
        
        $this->assertEquals("C\\D\\E\\MyClass", $resultFullName);
    }
    
    public function testCreate() {
        $offset = 2;
        $length = 3;
        
        $transformer = SliceNamespace::create([
            "offset" => $offset,
            "length" => $length
        ]);
        
        $this->assertEquals($offset, $transformer->getOffset());
        $this->assertEquals($length, $transformer->getLength());
        
    }
    
}
