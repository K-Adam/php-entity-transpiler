<?php

namespace EntityTranspiler\Utils\ClassRef\Transformer;

use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;

class Composition extends Transformer {

    /** @var Transformer[] */
    private $transformers;

    /** @param Transformer[] */
    function __construct(array $transformers) {
        $this->transformers = $transformers;
    }

    public function transform(ClassRef $ref): ClassRef {
        foreach($this->transformers as $transformer) {
            $ref = $transformer->transform($ref);
        }

        return $ref;
    }

    public function getTransformers(): array {
        return $this->transformers;
    }

    public static function create(array $params): Transformer {
        $transformers = [];
        foreach($params["transformers"] as $trData) {
            $transformers[] = Transformer::create($trData);
        }
        return new Composition($transformers);
    }

}
