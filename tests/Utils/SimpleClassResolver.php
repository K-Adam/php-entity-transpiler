<?php

namespace Tests\Utils;

use EntityTranspiler\Generators\Utils\ClassResolver\Rule;
use EntityTranspiler\Generators\Utils\ClassResolver;

class SimpleClassResolver extends ClassResolver {

    public function __construct(Rule $rule = null) {
        parent::__construct();

        $this->addRule($rule ?? new SimpleRule());
    }

}
