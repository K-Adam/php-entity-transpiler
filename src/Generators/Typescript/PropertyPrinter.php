<?php

namespace EntityTranspiler\Generators\Typescript;

use EntityTranspiler\Properties\Property;
use EntityTranspiler\Generators\Utils\ClassResolver;

class PropertyPrinter {

    private $typeNameResolver;

    public function __construct(TypeNameResolver $typeNameResolver) {
        $this->typeNameResolver = $typeNameResolver;
    }

    public function getPropertyString(Property $property): string {

        $pName = $property->name;

        $typeName = $this->typeNameResolver->getTypeName($property->type);
        $typeNames = [$typeName];

        if($property->nullable) {
            $typeNames[] = "null";
        }

        $pType = implode('|', $typeNames);

        $separator = $property->optional ? "?:" : ":";

        $pDefault = "";
        if(isset($property->default)) {
            if(is_string($property->default)) {
                $pValue = '"' . addslashes($property->default) . '"';
            } elseif(is_numeric($property->default)) {
                $pValue = strval($property->default);
            } else {
                throw new \Exception("Unknown default value type: ".$property->default);
            }

            $pDefault = " = $pValue";
        }

        return "$pName$separator $pType$pDefault";

    }

}
