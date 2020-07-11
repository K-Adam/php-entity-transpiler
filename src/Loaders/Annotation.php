<?php

namespace EntityTranspiler\Loaders;

use EntityTranspiler\Annotations as ET;
use ReflectionClass;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use EntityTranspiler\EntityCollection;
use EntityTranspiler\Sources\Source;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\Loaders\Annotation\EntityBuilder;

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

        $reader = new AnnotationReader();
        $reflectionClass = new ReflectionClass($classSource->getClassName());

        $entityAnnotation = $reader->getClassAnnotation($reflectionClass, ET\Entity::class);

        if (!$entityAnnotation) {
            return;
        }

        $builder = new EntityBuilder($reader);
        $entity = $builder->build($reflectionClass, $entityAnnotation);

        $this->collection->add($entity);

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
