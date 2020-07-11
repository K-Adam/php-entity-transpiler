<?php

namespace Tests\Unit\Generators;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\DependencyCollector;
use EntityTranspiler\Properties\PhpType;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Entity;

class DependencyCollectorTest extends TestCase {

    private $collector;

    protected function setUp(): void {
        $this->collector = new DependencyCollector();
    }

    public function testAliasDependencies() {

        $entity = new Entity("TestClass");
        $entity->type = Entity::TYPE_ALIAS;
        $entity->alias = new PhpType(PhpType::TYPE_CLASS, "MyNamespace\\MyClass");
        
        $this->assertEquals(["MyNamespace\\MyClass"], $this->collector->collectEntityDependencies($entity));
    }

    public function testMixedScalarType() {
        $mixedType = new PhpType(PhpType::TYPE_MIXED);
        $scalarType = new PhpType(PhpType::TYPE_SCALAR);

        $this->assertEquals([], $this->collector->getTypeDependency($mixedType));
        $this->assertEquals([], $this->collector->getTypeDependency($scalarType));
    }

    public function testClassType() {
        $classType = new PhpType(PhpType::TYPE_CLASS, "MyNamespace\\MyClass");

        $this->assertEquals(["MyNamespace\\MyClass"], $this->collector->getTypeDependency($classType));
    }

    public function testArrayType() {
        $mixedType = new PhpType(PhpType::TYPE_MIXED);
        $classType = new PhpType(PhpType::TYPE_CLASS, "MyNamespace\\MyClass");

        $mixedArray = new PhpType(PhpType::TYPE_ARRAY, $mixedType);
        $classArray = new PhpType(PhpType::TYPE_ARRAY, $classType);

        $this->assertEquals([], $this->collector->getTypeDependency($mixedArray));
        $this->assertEquals(["MyNamespace\\MyClass"], $this->collector->getTypeDependency($classArray));
    }

    public function testObjectType() {
        $mixedType = new PhpType(PhpType::TYPE_MIXED);
        $classType = new PhpType(PhpType::TYPE_CLASS, "MyNamespace\\MyClass");

        $mixedObject = new PhpType(PhpType::TYPE_OBJECT, $mixedType);
        $classObject = new PhpType(PhpType::TYPE_OBJECT, $classType);

        $this->assertEquals([], $this->collector->getTypeDependency($mixedObject));
        $this->assertEquals(["MyNamespace\\MyClass"], $this->collector->getTypeDependency($classObject));
    }

    public function testMergeDependencies() {
        $this->assertEqualsCanonicalizing(
            [
                "output1" => ["MyClass1", "MyClass2"],
                "output2" => ["MyClass3", "MyClass4"]
            ],
            $this->collector->mergeDependencies([
                [
                    "output1" => ["MyClass1", "MyClass2"],
                    "output2" => ["MyClass3"]
                ],
                [
                    "output2" => ["MyClass3", "MyClass4"]
                ]
          ])
        );
    }

    public function testGetHierarchyDependencies() {

        $entity = new Entity("MyClass");
        $entity->parentClass = new ClassRef("BaseClass");

        $this->assertEquals(["BaseClass"], $this->collector->getHierarchyDependencies($entity));

    }

}
