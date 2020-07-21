<?php

namespace EntityTranspiler\Utils\Exceptions;

use Exception;

class MissingParameter extends Exception {

    public function __construct(string $name) {
        parent::__construct("Missing parameter: $name");
    }

}
