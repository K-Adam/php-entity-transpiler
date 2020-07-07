<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class Entity {

    /** @Enum({"CLASS", "ENUM"}) */
    public $type = "CLASS";

}
