<?php

namespace EntityTranspiler\Utils;

use EntityTranspiler\Utils\Exceptions\MissingParameter;
use EntityTranspiler\Utils\Exceptions\InvalidParameterType;

class ParameterValidator {

    /** @var array */
    private $params;

    public function __construct(array $params) {
        $this->params = $params;
    }

    public function assertDefined(string $name): void {
        if(!isset($this->params[$name])) {
            throw new MissingParameter($name);
        }
    }

    public function assertType(string $name, string $type): void {
        if(!isset($this->params[$name])) return;

        $paramType = gettype($this->params[$name]);
        if($paramType != $type) {
            throw new InvalidParameterType($name, $type, $paramType);
        }
    }

    public function assert(string $name, string $type): void {
        $this->assertDefined($name);
        $this->assertType($name, $type);
    }

}
