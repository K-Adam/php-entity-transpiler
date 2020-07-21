<?php

namespace Tests\Unit\SourceExplorers;

use Tests\TestCase;
use EntityTranspiler\SourceExplorers\ClassFinder;

use Tests\Unit\SourceExplorers\ClassFinderProject as ExampleProject;

class ClassFinderTest extends TestCase {

    private $path;
    private $finder;

    protected function setUp(): void {
        $this->path = __DIR__."/ClassFinderProject";
        $this->finder = new ClassFinder(__DIR__."/ClassFinderProject");
    }

    public function testSimpleProjectFiles() {

        $sources = $this->finder->getSources();

        $sourceClassNames = array_map(function($source) {
            return $source->getClassName();
        }, $sources);

        $expectedSourceClassNames = [
            ExampleProject\MyClass01::class,
            ExampleProject\MyClass02::class,
            ExampleProject\MyNamespace\MyClass03::class
        ];

        $this->assertEqualsCanonicalizing(
            $expectedSourceClassNames,
            $sourceClassNames
        );
    }

    public function testCreate() {
        $finder = ClassFinder::create(["path" => $this->path]);

        $this->assertEquals($this->path, $finder->getPath());
    }

}
