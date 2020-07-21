<?php

namespace Tests\Utils;

use EntityTranspiler\Generators\Utils\ClassResolver;

class SimpleClassResolver extends ClassResolver {

    public function __construct() {
        parent::__construct();

        $this->addRule(new SimpleRule());
    }

}
