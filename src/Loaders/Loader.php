<?php

namespace EntityTranspiler\Loaders;

use EntityTranspiler\EntityCollection;
use EntityTranspiler\Sources\Source;

abstract class Loader {

    protected $collection;

    public function __construct(EntityCollection $collection = null) {
        $this->collection = $collection ?? new EntityCollection();
    }

    public abstract function processSource(Source $source);

    public function flush(): EntityCollection {
        $collection = $this->collection;
        $this->collection = new EntityCollection();
        return $collection;
    }

}
