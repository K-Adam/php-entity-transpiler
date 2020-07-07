<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver;

use Exception;

class CanNotMatchRule extends Exception {
    
    function __construct(string $message = "Can not match rule") {
        parent::__construct($message);
    }
    
}
