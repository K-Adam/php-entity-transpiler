<?php

namespace EntityTranspiler;

use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Properties\Property;
use EntityTranspiler\Properties\EnumValue;

class Entity {

    /** @var ClassRef */
    private $classRef;

    /** @var Property[] */
    public $properties = [];

    /** @var EnumValue[] */
    public $enumValues = [];

    const TYPE_CLASS = "CLASS";
    const TYPE_ENUM = "ENUM";

    /** @var string */
    public $type = self::TYPE_CLASS;

    /** @var ClassRef */
    public $parentClass;

    function __construct(string $fullName) {
        $this->classRef = new ClassRef($fullName);
    }

    public function getClassRef(): ClassRef {
        return $this->classRef;
    }

    public function getProperty(string $name): ?Property {
        foreach($this->properties as $property) {
            if ($property->name == $name) {
                return $property;
            }
        }

        return null;
    }

    public function hasProperty(string $name): bool {
        return !is_null($this->getProperty($name));
    }

}
