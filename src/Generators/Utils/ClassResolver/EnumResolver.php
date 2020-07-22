<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver;

use EntityTranspiler\Utils\NameFormat\Parser;
use EntityTranspiler\Utils\NameFormat\Writer;

use EntityTranspiler\Utils\ParameterValidator;

class EnumResolver {

    public $propertyNameFormat = null;

    public function resolvePropertyName(string $name): string {
        if(!$this->propertyNameFormat) return $name;

        $parser = new Parser();
        $writer = new Writer();

        return $writer->write($this->propertyNameFormat, $parser->parse($name));
    }

    public static function create(array $params): EnumResolver {

        $validator = new ParameterValidator($params);
        $validator->assert("propertyNameFormat", "string");

        $resolver = new EnumResolver();
        $resolver->propertyNameFormat = $params["propertyNameFormat"];
        return $resolver;

    }

}
