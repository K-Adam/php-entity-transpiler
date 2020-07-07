<?php

namespace Tests\Unit\Utils\ClassRef\Transformer;

use Tests\TestCase;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ClassRef\Transformer\PrependNamespace;
use EntityTranspiler\Utils\NameFormat\Writer;

class PrependNamespaceTest extends TestCase {
    
    public function testSimple() {
        $transformer = new PrependNamespace(2);
        
        $myClass = new ClassRef("Ns1\\Ns2\\Ns3\\Ns4\\MyClass");
        $transformedClass = $transformer->transform($myClass);
        
        $this->assertEquals("Ns1\\Ns2\\Ns3Ns4MyClass", $transformedClass->getFullName());
    }
    
    public function testWithNamespaceFormat() {
        $transformer = new PrependNamespace(2, Writer::SNAKE_CASE);
        
        $myClass = new ClassRef("Ns1\\Ns2\\Ns3\\Ns4\\MyClass");
        $transformedClass = $transformer->transform($myClass);
        
        $this->assertEquals("Ns1\\Ns2\\Ns3_Ns4_MyClass", $transformedClass->getFullName());
    }
    
    public function testCreate() {
        
        $offset = 2;
        $nsFormat = Writer::SNAKE_CASE;
        
        $transformer = PrependNamespace::create([
            "offset" => $offset,
            "nsFormat" => $nsFormat
        ]);
        
        $this->assertEquals($offset, $transformer->getOffset());
        $this->assertEquals($nsFormat, $transformer->getNsFormat());
        
    }
    
}
