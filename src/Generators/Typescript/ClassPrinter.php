<?php

namespace EntityTranspiler\Generators\Typescript;

use EntityTranspiler\Entity;
use EntityTranspiler\Utils\Indentation;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Utils\NameFormat\Parser;

class ClassPrinter {

    /** @var Indentation */
    private $indentation;

    /** @var ClassResolver */
    public $classResolver;

    /** @var Transformer */
    public $transformer;

    /** @var string */
    public $enumNameFormat;

    public function __construct(Indentation $indentation) {
        $this->indentation = $indentation;
    }

    public function getClassString(Entity $entity): string {

        switch($entity->type) {
            case Entity::TYPE_CLASS:
                return $this->getEntityClassString($entity);
            case Entity::TYPE_ENUM:
                return $this->getEntityEnumString($entity);
            case Entity::TYPE_ALIAS:
                return $this->getEntityAliasString($entity);
        }

        throw new \Exception("Unknown entity type: ".$entity->type);

    }

    public function getClassHeader(Entity $entity) {
        $classRef = $entity->getClassRef();
        $className = $this->getClassName($classRef);

        if($entity->parentClass) {
            $pName = $this->classResolver->resolveClassName($entity->parentClass);
            return "class $className extends $pName";
        } else {
            return "class $className";
        }

    }

    public function getClassName(ClassRef $ref) {
        if($this->transformer) {
            $ref = $this->transformer->transform($ref);
        }

        return $ref->getName();
    }

    //

    private function getEntityClassString(Entity $entity): string {
        $ident = $this->indentation->get();

        if($this->classResolver) {
            $printer = new PropertyPrinter($this->classResolver);
        } else {
            $printer = new PropertyPrinter();
        }

        $propText = "";
        foreach($entity->properties as $property) {
            $propText .= $ident . $printer->getPropertyString($property) . ";\n";
        }

        $header = $this->getClassHeader($entity);

        return "$header {\n$propText}";
    }

    private function getEntityEnumString(Entity $entity): string {
        $ident = $this->indentation->get();
        $enumRows = [];

        foreach($entity->enumValues as $enumValue) {

            $eValue = is_numeric($enumValue->value)? $enumValue->value : ('"' . $enumValue->value . '"');
            $eName = $enumValue->name;

            if($this->enumNameFormat) {
                $eName = (new Writer())->write($this->enumNameFormat, (new Parser())->parse($eName));
            }

            $enumRows[] = $ident . $eName . '=' . $eValue;
        }

        $enumText = implode(",\n", $enumRows);
        if(count($enumRows)>0) $enumText .= "\n";

        $className = $this->getClassName($entity->getClassRef());

        return "enum $className {\n{$enumText}}";
    }

    private function getEntityAliasString(Entity $entity): string {
      $typeNameResolver = new TypeNameResolver();

      if($this->classResolver) {
          $typeNameResolver->classResolver = $this->classResolver;
      }

      $className = $this->getClassName($entity->getClassRef());
      $aliasName = $typeNameResolver->getTypeName($entity->alias);

      return "type $className = $aliasName";
    }

}
