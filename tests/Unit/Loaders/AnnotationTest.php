<?php

namespace Tests\Unit\Loaders;

use Tests\TestCase;
use EntityTranspiler\Loaders\Annotation as AnnotationLoader;
use EntityTranspiler\EntityCollection;

use ReflectionClass;
use EntityTranspiler\Annotations as ET;
use Doctrine\Common\Annotations\AnnotationReader;
use EntityTranspiler\Sources\PhpClass;

use EntityTranspiler\Properties\PhpType;

class AnnotationTest extends TestCase {

    private $loader;
    private $prophet;

    protected function setUp(): void {
        $this->loader = new AnnotationLoader();
        $this->prophet = new \Prophecy\Prophet;
    }

    public function testBuilderWasCalled() {

        $entityAnnotation = new ET\Entity();

        $pReflectionClass = $this->prophet->prophesize();
        $pReflectionClass->willExtend(ReflectionClass::class);
        $pReflectionClass->getName()->willReturn("MyClass");
        $pReflectionClass->getProperties()->willReturn([]);
        $dummyReflector = $pReflectionClass->reveal();

        $pClass = $this->prophet->prophesize();
        $pClass->willExtend(PhpClass::class);
        $pClass->getReflectionClass()->willReturn($dummyReflector);
        $dummyClass = $pClass->reveal();

        $pReader = $this->prophet->prophesize();
        $pReader->willExtend(AnnotationReader::class);
        $pReader->getClassAnnotation($dummyReflector, ET\Entity::class)->willReturn($entityAnnotation);
        $dummyReader = $pReader->reveal();

        $loader = new AnnotationLoader(null, $dummyReader);
        $loader->processSource($dummyClass);
        $entities = $loader->flush()->getEntities();

        $entity = $entities[0];

        $this->assertEquals("MyClass", $entity->getClassRef());
    }

}
