<?php

namespace Tests\Utils;

use EntityTranspiler\Generators\Utils\ClassResolver\Rule;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver\SingleFile as SingleFileResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Utils\NameFormat\Writer;

class SimpleRule extends Rule {

    public function __construct() {
        parent::__construct(
            new SingleFileResolver("output"),
            new ClassNameResolver(Writer::PASCAL_CASE)
        );
    }
}
