<?php

namespace Tests\Unit\Utils;

use Tests\TestCase;
use EntityTranspiler\Utils\ParameterValidator;
use EntityTranspiler\Utils\Exceptions\MissingParameter;
use EntityTranspiler\Utils\Exceptions\InvalidParameterType;

class ParameterValidatorTest extends TestCase {
    
    public function testAssertDefined() {
        $obj = ["foo"=>"value"];
        $validator = new ParameterValidator($obj);
        
        $validator->assertDefined("foo");
        
        $this->expectException(MissingParameter::class);
        $validator->assertDefined("bar");
    }
    
    public function testAssertType() {
        $obj = ["foo"=>"value"];
        $validator = new ParameterValidator($obj);
        
        $validator->assertType("foo", "string");
        
        // if param is not defined, don't throw
        $validator->assertType("bar", "string");
        
        $this->expectException(InvalidParameterType::class);
        $validator->assertType("foo", "integer");
        
    }
    
}
