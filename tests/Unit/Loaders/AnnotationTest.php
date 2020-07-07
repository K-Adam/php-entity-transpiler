<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Loaders\Annotation as AnnotationLoader;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\EntityCollection;

use EntityTranspiler\Properties\PhpType;

class AnnotationTest extends TestCase {

    public function testClass() {

        $cnameAnnotated = \TestClasses\SimpleProject01\MyClass01::class;
        $cnameEmpty = \TestClasses\SimpleProject01\MyClass02::class;

        $testClassAnnotated = new PhpClass($cnameAnnotated);
        $testClassEmpty = new PhpClass($cnameEmpty);

        $loader = new AnnotationLoader();

        $loader->processSource($testClassAnnotated);
        $loader->processSource($testClassEmpty);
        
        $collection = $loader->load();

        $this->assertTrue($collection->hasName($cnameAnnotated));
        $this->assertFalse($collection->hasName($cnameEmpty));

    }

    public function testProperties() {
        $cname = \TestClasses\SimpleProject01\MyClass01::class;
        $testClass = new PhpClass($cname);

        $loader = new AnnotationLoader();

        $loader->processSource($testClass);
        $collection = $loader->load();
        
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
