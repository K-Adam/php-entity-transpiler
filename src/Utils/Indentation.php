<?php

namespace EntityTranspiler\Utils;

use EntityTranspiler\Utils\ParameterValidator;

class Indentation {

    private $type;
    private $count;

    const TYPE_SPACE = " ";
    const TYPE_TAB = "\t";

    public function __construct(string $type, int $count) {
        $this->type = $type;
        $this->count = $count;
    }

    public function get(int $size = 1) {
        return str_repeat($this->type, $this->count * $size);
    }

    function getType(): string {
        return $this->type;
    }

    function getCount(): int {
        return $this->count;
    }

    public static function create(array $params): Indentation {

        $validator = new ParameterValidator($params);

        $validator->assert("type", "string");
        $validator->assert("count", "integer");

        return new Indentation($params["type"], $params["count"]);

    }

}
