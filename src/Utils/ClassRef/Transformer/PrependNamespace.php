<?php

namespace EntityTranspiler\Utils\ClassRef\Transformer;

use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Utils\ParameterValidator;

class PrependNamespace extends Transformer{
    
    private $offset;
    private $nsFormat;
    
    function __construct(int $offset, string $nsFormat = null) {
        $this->offset = $offset;
        $this->nsFormat = $nsFormat;
    }

    public function transform(ClassRef $ref): ClassRef {
        
        $newNs = array_slice($ref->getNamespaceChain(), 0, $this->offset);
        $nameParts = array_merge(array_slice($ref->getNamespaceChain(), $this->offset), [$ref->getName()]);
        
        if($this->nsFormat) {
            // TODO: Parse NS?
            
            $writer = new Writer();
            $newName = $writer->write($this->nsFormat, $nameParts);
        } else {
            $newName = implode($nameParts);
        }
        
        return ClassRef::fromParsed($newNs, $newName);
        
    }
    
    public function getOffset() {
        return $this->offset;
    }

    public function getNsFormat() {
        return $this->nsFormat;
    }
   
    public static function create(array $params): Transformer {
        
        $validator = new ParameterValidator($params);
        
        $validator->assert("offset", "integer");
        $validator->assertType("nsFormat", "string");
        
        return new PrependNamespace($params["offset"], $params["nsFormat"] ?? null);
        
    }

}
