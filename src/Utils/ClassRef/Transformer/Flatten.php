<?php

namespace EntityTranspiler\Utils\ClassRef\Transformer;

use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;

class Flatten extends Transformer {
    
    public function transform(ClassRef $ref): ClassRef {
        return ClassRef::fromParsed([], $ref->getName());
    }
    
    public static function create(array $params): Transformer {
        return new Flatten();
    }

}
