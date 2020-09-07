<?php

namespace EntityTranspiler\Loaders;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use EntityTranspiler\EntityCollection;
use EntityTranspiler\Sources\Source;
use EntityTranspiler\Loaders\Annotation\SourceVisitor;

class Annotation extends Loader {

    /** @var SourceVisitor */
    private $visitor;

    public function __construct(EntityCollection $collection = null, AnnotationReader $reader = null) {
        parent::__construct($collection);

        $reader = $reader ?? new AnnotationReader();
        $this->visitor = new SourceVisitor($reader, $this->collection);

        AnnotationRegistry::registerLoader('class_exists');
    }

    public function processSource(Source $source) {
        $source->acceptVisitor($this->visitor);
    }

    public static function create(array $params, EntityCollection $collection = null): Annotation {
        return new Annotation($collection);
    }

}
