<?php

namespace Tests\Unit\Generators\Utils\ClassResolver\PathResolver;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver\SingleFile;
use EntityTranspiler\Utils\ClassRef;

class SingleFileTest extends TestCase {
    
    public function testResolve() {
        $resolver = new SingleFile("path/to/output");
        
        $this->assertEquals("path/to/output", $resolver->resolve(new ClassRef("MyClass")));
    }
    
    public function testCreate() {
        $path = "path/to/output";
        $resolver = SingleFile::create(["path"=>$path]);
        
        $this->assertEquals("path/to/output", $resolver->getPath());
    }
    
}
