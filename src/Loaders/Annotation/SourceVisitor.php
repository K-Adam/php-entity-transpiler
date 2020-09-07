<?php

namespace EntityTranspiler\Loaders\Annotation;

use EntityTranspiler\Sources\SourceVisitor as AbstractVisitor;
use EntityTranspiler\Loaders\Annotation\EntityBuilder;
use EntityTranspiler\EntityCollection;
use Doctrine\Common\Annotations\AnnotationReader;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\Annotations as ET;

class SourceVisitor extends AbstractVisitor {

    /** @var AnnotationReader */
    private $reader;

    /** @var EntityCollection */
    private $collection;

    public function __construct(AnnotationReader $reader, EntityCollection $collection) {
        $this->reader = $reader;
        $this->collection = $collection;
    }

    public function visitPhpClass(PhpClass $source) {
        $reflectionClass = $source->getReflectionClass();

        $entityAnnotation = $this->reader->getClassAnnotation($reflectionClass, ET\Entity::class);

        if(!$entityAnnotation) return;

        $builder = new EntityBuilder($this->reader);
        $entity = $builder->build($reflectionClass, $entityAnnotation);

        $this->collection->add($entity);
    }

}
