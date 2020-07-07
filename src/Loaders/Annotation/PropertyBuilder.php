<?php

namespace EntityTranspiler\Loaders\Annotation;

use EntityTranspiler\Annotations as ET;
use EntityTranspiler\Properties\Property;

use EntityTranspiler\Loaders\TypeParser;

class PropertyBuilder {

    private $parser;

    function __construct() {
        $this->parser = new TypeParser();
    }

    public function build(
        string $name,
        ET\Property $annotation
    ): Property {

        $type = $this->parser->parse($annotation->type);

        $property = new Property($name, $type);

        $property->default = $annotation->default;

        $property->optional = $annotation->optional;
        $property->nullable = $annotation->nullable;

        return $property;
    }

}
