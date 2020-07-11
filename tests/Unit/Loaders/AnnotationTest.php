<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Annotations as ET;
use EntityTranspiler\Loaders\Annotation as AnnotationLoader;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\EntityCollection;

use EntityTranspiler\Properties\PhpType;

class AnnotationTest extends TestCase {

    private $loader;

    protected function setUp(): void {
        $this->loader = new AnnotationLoader();
    }

    public function testClass() {

        $cnameAnnotated = \TestClasses\SimpleProject01\MyClass01::class;
        $cnameEmpty = \TestClasses\SimpleProject01\MyClass02::class;

        $testClassAnnotated = new PhpClass($cnameAnnotated);
        $testClassEmpty = new PhpClass($cnameEmpty);

        $this->loader->processSource($testClassAnnotated);
        $this->loader->processSource($testClassEmpty);

        $collection = $this->loader->load();

        $this->assertTrue($collection->hasName($cnameAnnotated));
        $this->assertFalse($collection->hasName($cnameEmpty));

    }

    public function testProperties() {
        $cname = \TestClasses\SimpleProject01\MyClass01::class;
        $testClass = new PhpClass($cname);

        $this->loader = new AnnotationLoader();

        $this->loader->processSource($testClass);
        $collection = $this->loader->load();

        $entity = $collection->getByName($cname);

        $this->assertTrue($entity->hasProperty('testString'));
        $this->assertTrue($entity->hasProperty('testInt'));
        $this->assertFalse($entity->hasProperty('testSkip'));
        $this->assertFalse($entity->hasProperty('nonExistent'));

        $stringProperty = $entity->getProperty('testString');
        $this->assertEquals(PhpType::TYPE_SCALAR, $stringProperty->type->type);
        $this->assertEquals('string', $stringProperty->type->value);

        $intProperty = $entity->getProperty('testInt');
        $this->assertEquals(PhpType::TYPE_SCALAR, $intProperty->type->type);
        $this->assertEquals('int', $intProperty->type->value);
    }

}
