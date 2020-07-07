<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"PROPERTY"})
 */
class Property {

    // string|Collection
    /** @var mixed */
    public $type = "mixed";

    /** @var mixed */
    public $default;

    /** @var bool */
    public $optional = false;

    /** @var bool */
    public $nullable = false;

}
