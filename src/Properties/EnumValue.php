<?php

namespace EntityTranspiler\Properties;

class EnumValue {

    /** @var string */
    public $name;
    public $value;

    public function __construct(string $name, $value) {
        $this->name = $name;
        $this->value = $value;
    }

}
