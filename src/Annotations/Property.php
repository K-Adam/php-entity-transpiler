<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Property {

    // string|Collection|Map
    /** @var mixed */
    public $type = "mixed";

    /** @var string */
    public $name = null;

    /** @var mixed */
    public $default;

    /** @var bool */
    public $optional = false;

    /** @var bool */
    public $nullable = false;

}
