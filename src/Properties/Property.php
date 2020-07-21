<?php

namespace EntityTranspiler\Properties;

class Property {

    public $name;
    public $type;
    public $default;
    public $nullable = false;
    public $optional = false;

    public function __construct(string $name, PhpType $type) {
      $this->name = $name;
      $this->type = $type;
    }

}
