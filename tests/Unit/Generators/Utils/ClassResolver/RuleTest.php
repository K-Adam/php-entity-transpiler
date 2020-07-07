<?php

namespace Tests\Unit\Generators\ClassResolver;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\ClassResolver\Rule;

class RuleTest extends TestCase{
    
    public function testCreate() {
        
        $rule = Rule::create([
            "source"=>"Foo\\Bar\\MyClass",
            "pathResolver"=>["type"=>"SINGLE_FILE", "path"=>"dummy"],
            "classNameResolver"=>["format"=>"CAMEL_CASE"]
        ]);
        
        $this->assertEquals(["Foo", "Bar"], $rule->namespaceChain);
        $this->assertEquals("MyClass", $rule->className);
        
    }
    
}
