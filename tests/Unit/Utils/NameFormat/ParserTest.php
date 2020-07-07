<?php

namespace Tests\Unit\Utils\NameFormat;

use Tests\TestCase;
use EntityTranspiler\Utils\NameFormat\Parser;

class ParserTest extends TestCase {

    /** @var Parser */
    private $parser;

    protected function setUp(): void {
        $this->parser = new Parser();
    }

    public function testSingle() {

        $this->assertEquals(["foo"], $this->parser->parse("foo"));
        $this->assertEquals(["bar"], $this->parser->parse("Bar"));

    }

    public function testCamelCase() {

        $this->assertEquals(["foo", "bar"], $this->parser->parse("fooBar"));

    }

    public function testPascalCase() {

        $this->assertEquals(["foo", "bar"], $this->parser->parse("FooBar"));

    }

    public function testSnakeCase() {

        $this->assertEquals(["foo", "bar"], $this->parser->parse("foo_bar"));
        $this->assertEquals(["foo", "bar"], $this->parser->parse("foo__bar"));

    }

    public function testMixedCase() {

        $this->assertEquals(["foo", "bar"], $this->parser->parse("Foo_bar"));

    }

    public function testConstName() {

        $this->assertEquals(["foo", "bar"], $this->parser->parse("FOO_BAR"));

    }

}
