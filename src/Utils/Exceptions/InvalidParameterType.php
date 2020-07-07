<?php

namespace EntityTranspiler\Utils\Exceptions;

use Exception;

class InvalidParameterType extends Exception {
    
    function __construct(string $name, string $expectedType, string $actualType) {
        parent::__construct("Invalid type $actualType for parameter $name. Expected: $expectedType");
    }
    
}
