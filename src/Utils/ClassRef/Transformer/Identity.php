<?php

namespace EntityTranspiler\Utils\ClassRef\Transformer;

use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;


class Identity extends Transformer{
    
    public function transform(ClassRef $ref): ClassRef {
        return $ref;
    }

    public static function create(array $params): Transformer {
        return new Flatten();
    }
    
}
