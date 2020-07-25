<?php

namespace EntityTranspiler\Generators\Typescript;

use EntityTranspiler\Properties\PhpType;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Utils\ClassRef;

class TypeNameResolver {

    /** @var ClassResolver */
    private $classResolver;

    public function __construct(ClassResolver $classResolver) {
        $this->classResolver = $classResolver;
    }

    public function getTypeName(PhpType $pType): string {

        switch($pType->type) {

            case PhpType::TYPE_MIXED:
                return $this->getMixedType();

            case PhpType::TYPE_SCALAR:
                return $this->getScalarType($pType->value);

            case PhpType::TYPE_ARRAY:
                return $this->getArrayType($pType->value);

            case PhpType::TYPE_OBJECT:
                return $this->getObjectType($pType->value, $pType->key, $pType->keyName);

            case PhpType::TYPE_CLASS:
                return $this->getClassType($pType->value);

            default:
                throw new \Exception("Unknown type: ".$pType->type);

        }

    }

    private function getMixedType(): string {
        return "any";
    }

    private function getScalarType($tValue): string {
        $replaceTypes = [
            "int" => "number",
            "float" => "number",
            "bool" => "boolean"
        ];

        $typeName = str_replace(
            array_keys($replaceTypes),
            array_values($replaceTypes),
            $tValue
        );

        return $typeName;
    }

    private function getArrayType($tValue): string {
        $subName = $this->getTypeName($tValue);
        return "Array<$subName>";
    }

    private function getObjectType($tValue, $tKey, $nKey): string {
        $keyType = ($tKey=="int") ? "number" : "string";
        $valType = $this->getTypeName($tValue);
        return "{[$nKey:$keyType]:$valType}";
    }

    private function getClassType($tValue): string {
        $ref = new ClassRef($tValue);

        if($ref->isBuiltIn()) {
            switch($tValue) {
                case "DateTime":
                    return "Date";
                default:
                    throw new \Exception("Built in type cannot be resolved: $tValue");
            }
        }

        return $this->classResolver->resolveClassName($ref);
    }

}
