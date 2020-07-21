<?php

namespace EntityTranspiler\Utils\ClassRef\Transformer;

use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ParameterValidator;

class SliceNamespace extends Transformer{

    private $offset;
    private $length;

    public function __construct(int $offset, int $length = null) {
        $this->offset = $offset;
        $this->length = $length;
    }


    public function transform(ClassRef $ref): ClassRef {
        return ClassRef::fromParsed(array_slice($ref->getNamespaceChain(), $this->offset, $this->length), $ref->getName());
    }

    public function getOffset() {
        return $this->offset;
    }

    public function getLength() {
        return $this->length;
    }

    public static function create(array $params): Transformer {

        $validator = new ParameterValidator($params);

        $validator->assert("offset", "integer");
        $validator->assertType("length", "integer");

        return new SliceNamespace($params["offset"], $params["length"] ?? null);

    }

}
