<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;

use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;
use EntityTranspiler\Utils\ParameterValidator;

class SingleFile extends PathResolver {
    
    /** @var string */
    private $path;
    
    /** @param string $path Output path without extension*/
    function __construct(string $path) {
        $this->path = $path;
    }

    public function resolve(\EntityTranspiler\Utils\ClassRef $ref): string {
        return $this->path;
    }
    
    public function getPath(): string {
        return $this->path;
    }

    public static function create(array $params): PathResolver {
        
        (new ParameterValidator($params))->assert("path", "string");
        
        return new SingleFile($params["path"]);
        
    }

}
