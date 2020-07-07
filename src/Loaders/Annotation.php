<?php

namespace EntityTranspiler\Loaders;

use EntityTranspiler\Annotations as ET;
use ReflectionClass;
use ReflectionProperty;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use EntityTranspiler\EntityCollection;
use EntityTranspiler\Sources\Source;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\Entity;
use EntityTranspiler\Loaders\Annotation\PropertyBuilder;
use EntityTranspiler\Properties\EnumValue;
use EntityTranspiler\Utils\ClassRef;

class Annotation implements Loader {

    /** @var EntityCollection */
    private $collection;

    function __construct() {
        $this->collection = new EntityCollection();

        AnnotationRegistry::registerLoader('class_exists');
    }

    public function processSource(Source $source) {

        if (!$source instanceof PhpClass) {
            return;
        }

        /** @var PhpClass * */
        $classSource = $source;

        $reflectionClass = new ReflectionClass($classSource->getClassName());

        $reader = new AnnotationReader();

        $entityAnnotation = $reader->getClassAnnotation($reflectionClass, ET\Entity::class);

        if (!$entityAnnotation) {
            return;
        }

        $entity = new Entity($classSource->getClassName());
        $this->collection->add($entity);

        $parent = $reflectionClass->getParentClass();
        if($parent) {
            $entity->parentClass = new ClassRef($parent->getName());
        }

        switch($entityAnnotation->type) {
            case "CLASS":
                $entity->type = Entity::TYPE_CLASS;
                $this->loadProperties($entity, $reader, $reflectionClass);
                break;
            case "ENUM":
                $entity->type = Entity::TYPE_ENUM;
                $this->loadEnumValues($entity, $reader, $reflectionClass);
                break;
            default:
                throw new \Exception("Unknown entity type: ".$entityAnnotation->type);
        }

    }

    private function loadProperties(Entity $entity, AnnotationReader $reader, ReflectionClass $reflectionClass) {
        $properties = $reflectionClass->getProperties();

        $builder = new PropertyBuilder();

        foreach ($properties as $property) {
            $pname = $property->getName();
            $propertyAnnotation = $reader->getPropertyAnnotation($property, ET\Property::class);

            if (!$propertyAnnotation) {
                continue;
            }

            $entity->properties[] = $builder->build($pname, $propertyAnnotation);
        }
    }

    private function loadEnumValues(Entity $entity, AnnotationReader $reader, ReflectionClass $reflectionClass) {
        $constants = $reflectionClass->getConstants();

        foreach ($constants as $name => $value) {
            $entity->enumValues[] = new EnumValue($name, $value);
        }

    }

    public function load(): EntityCollection {
        $collection = $this->collection;
        $this->collection = new EntityCollection();
        return $collection;
    }

    public static function create(array $params): Annotation {
        return new Annotation();
    }

}
