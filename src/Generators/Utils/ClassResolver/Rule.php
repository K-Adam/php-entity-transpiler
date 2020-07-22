<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver;

use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;
use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\ParameterValidator;

class Rule {

    /** @var string[] */
    public $namespaceChain = [];

    /** @var string */
    public $className = "*";

    /** @var PathResolver */
    public $pathResolver;

    /** @var ClassNameResolver */
    public $classNameResolver;

    /** @var EnumResolver */
    public $enumResolver;

    /** @var Transformer|null */
    public $transformer = null;

    public function __construct() {
        $this->enumResolver = new EnumResolver();
    }

    public static function create(array $params): Rule {

        $validator = new ParameterValidator($params);

        $validator->assert("source", "string");
        $validator->assert("pathResolver", "array");
        $validator->assert("classNameResolver", "array");
        $validator->assertType("enumResolver", "array");

        $nsParts = explode("\\", $params["source"]);
        $className = array_pop($nsParts);

        $rule = new Rule();
        $rule->namespaceChain = $nsParts;
        $rule->className = $className;
        $rule->pathResolver = PathResolver::create($params["pathResolver"]);
        $rule->classNameResolver = ClassNameResolver::create($params["classNameResolver"]);

        if(isset($params["transformer"])) {
            $rule->transformer = Transformer::create($params["transformer"]);
        }

        if(isset($params["enumResolver"])) {
            $rule->enumResolver = EnumResolver::create($params["enumResolver"]);
        }

        return $rule;

    }

}
