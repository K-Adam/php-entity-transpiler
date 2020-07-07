<?php

namespace EntityTranspiler\Generators\Utils;

use EntityTranspiler\Generators\Utils\ClassResolver\Rule;
use EntityTranspiler\Generators\Utils\ClassResolver\CanNotMatchRule;
use EntityTranspiler\Utils\ClassRef;

class ClassResolver {

    /** @var Rule[] */
    private $rules = [];

    public function addRule(Rule $rule) {
        $this->rules[] = $rule;
    }

    public function findRule(ClassRef $ref) :? Rule {

        // search from strict -> broad
        $ns = $ref->getNamespaceChain();
        do {

            foreach ($this->rules as $rule) {

                if (
                    $rule->namespaceChain == $ns &&
                    ($rule->className == "*" || $rule->className == $ref->getName())
                ) {
                    return $rule;
                }
            }
        } while (array_pop($ns));
        
        return null;
    }
    
    public function resolvePath(ClassRef $ref): string {
        
        [$rule, $ref] = $this->resolve($ref);
        
        return $rule->pathResolver->resolve($ref);
        
    }
    
    public function resolveClassName(ClassRef $ref): string {
        
        [$rule, $ref] = $this->resolve($ref);
        
        return $rule->classNameResolver->resolve($ref);
        
    }
    
    /** @return [Rule, ClassRef]*/
    public function resolve(ClassRef $ref): array {
        $rule = $this->findRule($ref);
        
        if(!$rule) {
            throw new CanNotMatchRule();
        }
        
        if($rule->transformer) {
            $ref = $rule->transformer->transform($ref);
        }
        
        return [$rule, $ref];
    }
    
    public static function create(array $ruleDatas): ClassResolver {
        
        $res = new ClassResolver();
        
        foreach($ruleDatas as $ruleData) {
            $res->addRule(Rule::create($ruleData));
        }
        
        return $res;
        
    }

}
