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
    * @var string
    */
    public $target = null;

}
