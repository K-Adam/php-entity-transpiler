<?php

namespace Tests\Unit\Utils\NameFormat;

use Tests\TestCase;
use EntityTranspiler\Utils\NameFormat\Writer;

class WriterTest extends TestCase {

    /** @var Writer */
    private $writer;

    protected function setUp(): void {
        $this->writer = new Writer();
    }

    public function testCamelCase() {
        $type = Writer::CAMEL_CASE;

        $this->assertEquals("fooBar", $this->writer->write($type, ["foo", "bar"]));
    }

    public function testPascalCase() {
        $type = Writer::PASCAL_CASE;

        $this->assertEquals("FooBar", $this->writer->write($type, ["foo", "bar"]));
    }

    public function testSnakeCase() {
        $type = Writer::SNAKE_CASE;

        $this->assertEquals("foo_bar", $this->writer->write($type, ["foo", "bar"]));
    }

    public function testKebabCase() {
        $type = Writer::KEBAB_CASE;

        $this->assertEquals("foo-bar", $this->writer->write($type, ["foo", "bar"]));
    }

}
