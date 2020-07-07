<?php

namespace Tests\Unit\Generators;

use Tests\TestCase;
use EntityTranspiler\Generators\Utils\RelativePathConverter;

class RelativePathConverterTest extends TestCase {

    private $converter;

    protected function setUp(): void {
        $this->converter = new RelativePathConverter("base/sub");
    }

    public function testTrivial() {
        $trivialConverter = new RelativePathConverter("");
        $this->assertEquals("./foo/bar.out", $trivialConverter->getPath("foo/bar.out"));
    }

    public function testNoSlash() {
        $trivialConverterNoSlash = new RelativePathConverter("", false);
        $this->assertEquals("foo/bar.out", $trivialConverterNoSlash->getPath("foo/bar.out"));
    }

    public function testNoExtension() {
      $trivialConverterNoSlash = new RelativePathConverter("", true, true);
      $this->assertEquals("./foo/bar", $trivialConverterNoSlash->getPath("foo/bar.out"));
    }

    public function testDescendants() {
        $this->assertEquals("./foo.out", $this->converter->getPath("base/sub/foo.out"));
        $this->assertEquals("./foo/bar.out", $this->converter->getPath("base/sub/foo/bar.out"));
    }

    public function testAncestors() {
        $this->assertEquals("../foo.out", $this->converter->getPath("base/foo.out"));
        $this->assertEquals("../../foo/bar.out", $this->converter->getPath("foo/bar.out"));
    }

}
