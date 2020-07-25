<?php

namespace Tests\Unit\Generators;

use Tests\TestCase;

use Tests\Utils\SimpleRule;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\CanNotMatchRule;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ClassRef\Transformer;

class ClassResolverTest extends TestCase {

    private $resolver;

    protected function setUp(): void {
        $this->resolver = new ClassResolver();
    }

    public function testSingleRule() {

        $rule = new SimpleRule();
        $rule->namespaceChain = ["Foo", "Bar"];
        $rule->className = "MyClass";

        $this->resolver->addRule($rule);

        $this->assertEquals($rule, $this->resolver->findRule(new ClassRef("Foo\\Bar\\MyClass")));

        $this->assertEquals(null, $this->resolver->findRule(new ClassRef("Foo\\Bar\\MyClass2")));

    }

    public function testStricterRule() {

        $rule1 = new SimpleRule();
        $rule1->namespaceChain = ["Foo"];
        $rule1->className = "ClassOne";

        $this->resolver->addRule($rule1);

        $rule2 = new SimpleRule();
        $rule2->namespaceChain = ["Foo", "Bar"];
        $rule2->className = "ClassTwo";

        $this->resolver->addRule($rule2);

        $this->assertEquals($rule2, $this->resolver->findRule(new ClassRef("Foo\\Bar\\ClassTwo")));
    }

    public function testResolveWithoutRoute() {

        $ref = new ClassRef("MyClass");

        $this->expectException(CanNotMatchRule::class);
        $this->resolver->resolve($ref);

    }

    public function testTransformedResolve() {

        $dummy = new class extends Transformer {
            public function transform(ClassRef $ref): ClassRef {
                return new ClassRef("TestClass");
            }
        };

        $rule = new SimpleRule();
        $rule->transformer = $dummy;
        $this->resolver->addRule($rule);

        [, $ref] = $this->resolver->resolve(new ClassRef("MyClass"));
        $this->assertEquals("TestClass", $ref->getFullName());

    }

}
