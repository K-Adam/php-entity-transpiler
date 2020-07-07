<?php

namespace Tests\Unit\SourceExplorers;

use Tests\TestCase;
use EntityTranspiler\SourceExplorers\ClassFinder;

class ClassFinderTest extends TestCase {

    public function testSimpleProjectFiles() {

        $finder = new ClassFinder("test_classes/SimpleProject01");
        $sources = $finder->getSources();

        $sourceClassNames = array_map(function($source) {
            return $source->getClassName();
        }, $sources);

        $expectedSourceClassNames = [
            \TestClasses\SimpleProject01\MyClass01::class,
            \TestClasses\SimpleProject01\MyClass02::class,
            \TestClasses\SimpleProject01\MyNamespace\MyClass03::class
        ];

        $this->assertEqualsCanonicalizing(
                $expectedSourceClassNames,
                $sourceClassNames
        );
    }
    
    public function testCreate() {
        $path = "test_classes/SimpleProject01";
        $finder = ClassFinder::create(["path" => $path]);
        
        $this->assertEquals($path, $finder->getPath());
    }

}
