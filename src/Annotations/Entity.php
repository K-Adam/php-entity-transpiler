<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Entity {

    /** @Enum({"CLASS", "ENUM", "ALIAS"}) */
    public $type = "CLASS";

    /**
    * Alias target
    * string|Collection|Map
    * @var mixed
    */
    public $target = null;

    /**
    * @var \EntityTranspiler\Annotations\Property[]
    */
    public $extraProperties = [];

}
