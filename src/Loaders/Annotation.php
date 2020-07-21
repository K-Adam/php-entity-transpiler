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

    /** @var AnnotationReader */
    private $reader;

    public function __construct(AnnotationReader $reader = null) {
        $this->reader = $reader ?? new AnnotationReader();
        $this->collection = new EntityCollection();

        AnnotationRegistry::registerLoader('class_exists');
    }

    public function processSource(Source $source) {

        if(!is_subclass_of($source, PhpClass::class)) return;

        $this->processPhpSource($source);

    }

    private function processPhpSource(PhpClass $classSource) {
        $reflectionClass = $classSource->getReflectionClass();

        $entityAnnotation = $this->reader->getClassAnnotation($reflectionClass, ET\Entity::class);

        if(!$entityAnnotation) return;

        $builder = new EntityBuilder($this->reader);
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
