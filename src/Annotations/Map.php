<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Map {

    // string|Collection|Map
    /** @var mixed */
    public $type = "mixed";

    // string|Collection|Map
    /** @var mixed */
    public $keyType = "string";

    /** @var string */
    public $keyName = "key";

    public function __toString(): string {
        $key = "[$this->keyName:$this->keyType]";
        return '{'."$key:$this->type".'}';
    }

}
