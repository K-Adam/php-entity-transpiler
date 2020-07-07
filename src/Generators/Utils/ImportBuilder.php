<?php

namespace EntityTranspiler\Generators\Utils;

use EntityTranspiler\Utils\ClassRef;

class ImportBuilder {
    
    /** @var ClassResolver */
    private $classResolver;

    private $result = [];

    function __construct(ClassResolver $classResolver) {
        $this->classResolver = $classResolver;
    }

    public function addDependencyClass(ClassRef $ref): void {
        $path = $this->classResolver->resolvePath($ref);
        $cName = $this->classResolver->resolveClassName($ref);
        
        $this->result[$path][] = $cName;
    }

    public function build(): array {
        return $this->result;
    }

}
