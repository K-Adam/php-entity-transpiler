<?php

namespace EntityTranspiler\Loaders\Annotation;

use EntityTranspiler\Annotations as ET;
use EntityTranspiler\Entity;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Properties\EnumValue;
use EntityTranspiler\Loaders\TypeParser;
use EntityTranspiler\Properties\PhpType;

use ReflectionClass;
use Doctrine\Common\Annotations\AnnotationReader;

class EntityBuilder {

    private $reader;

    public function __construct(AnnotationReader $reader) {
        $this->reader = $reader;
    }

    public function build(ReflectionClass $reflectionClass, ET\Entity $entityAnnotation): Entity {
        $entity = new Entity($reflectionClass->getName());

        $parent = $reflectionClass->getParentClass();
        if($parent) {
            $entity->parentClass = new ClassRef($parent->getName());
        }

        switch($entityAnnotation->type) {
            case Entity::TYPE_CLASS:
                $entity->type = Entity::TYPE_CLASS;
                $this->loadProperties($entity, $reflectionClass);
                $this->loadExtraProperties($entity, $entityAnnotation);
                break;
            case Entity::TYPE_ENUM:
                $entity->type = Entity::TYPE_ENUM;
                $this->loadEnumValues($entity, $reflectionClass);
                break;
            case Entity::TYPE_ALIAS:
                $entity->type = Entity::TYPE_ALIAS;
                $entity->alias = (new TypeParser())->parse($entityAnnotation->target);
                break;
            default:
                throw new \Exception("Unknown entity type: ".$entityAnnotation->type);
        }

        return $entity;
    }

    private function loadProperties(Entity $entity, ReflectionClass $reflectionClass) {
        $properties = $reflectionClass->getProperties();

        $builder = new PropertyBuilder();

        foreach ($properties as $property) {
            $pname = $property->getName();
            $propertyAnnotation = $this->reader->getPropertyAnnotation($property, ET\Property::class);

            if (!$propertyAnnotation) {
                continue;
            }

            $entity->properties[] = $builder->build($pname, $propertyAnnotation);
        }
    }

    private function loadExtraProperties(Entity $entity, ET\Entity $entityAnnotation) {
        $builder = new PropertyBuilder();

        foreach($entityAnnotation->extraProperties as $property) {

            if(is_null($property->name)) {
                throw new \Exception("Extra property cannot have NULL as name");
            }

            $entity->properties[] = $builder->build($property->name, $property);

        }
    }

    private function loadEnumValues(Entity $entity, ReflectionClass $reflectionClass) {
        $overrides = $this->reader->getClassAnnotation($reflectionClass, ET\OverrideEnumValues::class);

        if($overrides) {
            foreach ($overrides->getValues() as $name => $value) {
                $entity->enumValues[] = new EnumValue($name, $value);
            }
            return;
        }

        $constants = $reflectionClass->getConstants();
        foreach ($constants as $name => $value) {
            $entity->enumValues[] = new EnumValue($name, $value);
        }

    }

}
