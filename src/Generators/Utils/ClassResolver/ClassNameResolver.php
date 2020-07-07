<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver;

use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\NameFormat\Parser;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Utils\ParameterValidator;

class ClassNameResolver {
    
    /** @var string */
    private $format;

    function __construct(string $format) {
        $this->format = $format;
    }
    
    public function resolve(ClassRef $ref): string {
        $parser = new Parser();
        $writer = new Writer();

        return $writer->write($this->format, $parser->parse($ref->getName()));
    }
    
    public function getFormat(): string {
        return $this->format;
    }

    public static function create(array $params): ClassNameResolver {
        
        (new ParameterValidator($params))->assert("format", "string");
        
        return new ClassNameResolver($params["format"]);
    }
    
}
