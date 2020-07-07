<?php

namespace Tests\Unit\Generators\Utils\ClassResolver\PathResolver;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver\Directory;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\NameFormat\Writer;

class DirectoryTest extends TestCase {
    
    public function testSimple() {
         
        $dir = new Directory("output", Writer::SNAKE_CASE, Writer::KEBAB_CASE);
        
        $this->assertEquals("output/foo_bar/my-class", $dir->resolve(new ClassRef("FooBar\\MyClass")));
        
    }
    
    public function testCreate() {
        
        $path = "output";
        $dirNameFormat = Writer::SNAKE_CASE;
        $fileNameFormat = Writer::KEBAB_CASE;
        
        $dir = Directory::create([
            "path" => $path,
            "dirNameFormat" => $dirNameFormat,
            "fileNameFormat" => $fileNameFormat
        ]);
        
        $this->assertEquals($path, $dir->getPath());
        $this->assertEquals($dirNameFormat, $dir->getDirNameFormat());
        $this->assertEquals($fileNameFormat, $dir->getFileNameFormat());
        
    }
    
}
