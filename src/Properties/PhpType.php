<?php

namespace EntityTranspiler\Properties;

class PhpType {

    const TYPE_MIXED = 'TYPE_MIXED';
    const TYPE_SCALAR = 'TYPE_SCALAR';
    const TYPE_CLASS = 'TYPE_CLASS';
    const TYPE_ARRAY = 'TYPE_ARRAY';
    const TYPE_OBJECT = 'TYPE_OBJECT';

    /** @var string */
    public $type;

    /** @var string|PhpType|null */
    public $value;

    /** @var "string"|"int" */
    public $key;

    function __construct(string $type, $value = null, string $key = null) {
        $this->type = $type;
        $this->value = $value;
        $this->key = $key;
    }

}
