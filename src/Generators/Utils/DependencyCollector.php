<?php

namespace EntityTranspiler\Generators\Utils;

use EntityTranspiler\Entity;
use EntityTranspiler\Properties\PhpType;
use EntityTranspiler\Utils\ClassRef;

class DependencyCollector {

    /** @return string[] */
    public function collectEntityDependencies(Entity $entity): array {
        $result = $this->getHierarchyDependencies($entity);
        foreach($entity->properties as $property) {
            $result = array_merge($result, $this->getTypeDependency($property->type));
        }
        return $result;
    }

    public function getHierarchyDependencies(Entity $entity): array {
        return $entity->parentClass ? [$entity->parentClass] : [];
    }

    /** @return ClassRef[] */
    public function getTypeDependency(PhpType $type): array {

        switch($type->type) {
            case PhpType::TYPE_CLASS:
                $ref = new ClassRef($type->value);
                return $ref->isBuiltIn() ? [] : [$ref];
            case PhpType::TYPE_ARRAY:
                return $this->getTypeDependency($type->value);
            case PhpType::TYPE_OBJECT:
                return $this->getTypeDependency($type->value);
            default:
                return [];
        }

    }

    /**
    * @param ClassRef[][]
    * @return ClassRef[]
    */
    public function mergeDependencies(array $dependencyMaps): array {
        $res = [];
        foreach($dependencyMaps as $dependencyMap) {
            foreach($dependencyMap as $path => $dependencies) {
                $res[$path] = $res[$path] ?? [];

                $res[$path] = array_unique(array_merge($dependencies));
            }
        }
        return $res;
    }

}
