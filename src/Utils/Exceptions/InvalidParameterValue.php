<?php

namespace EntityTranspiler\Utils\Exceptions;

use Exception;

class InvalidParameterValue extends Exception {

    public function __construct(string $name, $value) {
        parent::__construct("Invalid value \"$value\" for parameter $name");
    }

}
