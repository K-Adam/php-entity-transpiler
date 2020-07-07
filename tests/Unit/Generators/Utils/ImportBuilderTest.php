<?php

namespace Tests\Unit\Generators;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\ImportBuilder;
use EntityTranspiler\Generators\Utils\ClassResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\Rule;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver\SingleFile as SingleFileResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver\Directory as DirectoryResolver;
use EntityTranspiler\Generators\Utils\ClassResolver\ClassNameResolver;
use EntityTranspiler\Utils\ClassRef;


class ImportBuilderTest extends TestCase {
    
    /** @var ClassResolver */
    private $resolver;
    
    /** @var ImportBuilder */
    private $builder;

    protected function setUp(): void {
        
        $this->resolver = new ClassResolver();
        $this->builder = new ImportBuilder($this->resolver);
        
    }

    public function testSingleClass() {
        $rule = new Rule();
        $rule->pathResolver = new DirectoryResolver("output", Writer::SNAKE_CASE, Writer::SNAKE_CASE);
        $rule->classNameResolver = new ClassNameResolver(Writer::CAMEL_CASE);
        $this->resolver->addRule($rule);
        
        $this->builder->addDependencyClass(new ClassRef("MyNamespace\\MyClass"));

        $this->assertEquals(["output/my_namespace/my_class" => ["myClass"]], $this->builder->build());
    }
    
    public function testMultipleFiles() {
        $rule = new Rule();
        $rule->pathResolver = new DirectoryResolver("output", Writer::SNAKE_CASE, Writer::SNAKE_CASE);
        $rule->classNameResolver = new ClassNameResolver(Writer::CAMEL_CASE);
        $this->resolver->addRule($rule);
        
        $this->builder->addDependencyClass(new ClassRef("MyNamespace\\Foo"));
        $this->builder->addDependencyClass(new ClassRef("MyNamespace\\Bar"));
        
        $expected = [
            "output/my_namespace/foo" => ["foo"],
            "output/my_namespace/bar" => ["bar"]
        ];

        $this->assertEquals($expected, $this->builder->build());
    }

    public function testMultipleInSingleFile() {
        $rule = new Rule();
        $rule->namespaceChain = ["Foo"];
        $rule->pathResolver = new SingleFileResolver("output/foo");
        $rule->classNameResolver = new ClassNameResolver(Writer::CAMEL_CASE);
        $this->resolver->addRule($rule);
        
        $this->builder->addDependencyClass(new ClassRef("Foo\\MyClass1"));
        $this->builder->addDependencyClass(new ClassRef("Foo\\MyClass2"));

        $this->assertEquals(["output/foo" => ["myClass1", "myClass2"]], $this->builder->build());
    }

}
