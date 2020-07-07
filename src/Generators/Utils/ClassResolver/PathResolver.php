<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver;

use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ParameterValidator;
use EntityTranspiler\Utils\Exceptions\InvalidParameterValue;

abstract class PathResolver {
    
    const TYPE_SINGLE_FILE = "SINGLE_FILE";
    const TYPE_DIRECTORY = "DIRECTORY";
    
    public abstract function resolve(ClassRef $ref): string;
    
    public static function create(array $params): PathResolver {
        
        (new ParameterValidator($params))->assert("type", "string");
        
        $type = $params["type"];
        switch($type) {
            
            case self::TYPE_SINGLE_FILE:
                return PathResolver\SingleFile::create($params);
                
            case self::TYPE_DIRECTORY:
                return PathResolver\Directory::create($params);
                
            default:
                throw new InvalidParameterValue("type", $type);
            
        }
        
    }
    
}
