<?php

namespace Tests\Unit\Generators\Typescript;

use Tests\TestCase;
use EntityTranspiler\Generators\Typescript\ImportPrinter;
use EntityTranspiler\Generators\Utils\RelativePathConverter;

class ImportPrinterTest extends TestCase {

    /** @var ImportPrinter */
    private $printer;

    protected function setUp(): void {
        $this->printer = new ImportPrinter(
            new RelativePathConverter("foo")
        );
    }

    public function testSingleClassImportLine() {
        $this->assertEquals(
            'import { MyClass } from "./my-class";',
            $this->printer->getImportLine("foo/my-class", ["MyClass"])
        );
    }

    public function testMultiClassImportLine() {
        $this->assertEquals(
            'import { MyClassOne, MyClassTwo } from "./entities";',
            $this->printer->getImportLine("foo/entities", ["MyClassOne", "MyClassTwo"])
        );
    }

    public function testImportString() {
        $this->assertEquals(
            'import { MyClass } from "./my-class";'."\n".'import { OtherClass } from "./other-class";',
            $this->printer->getImportString([
                "foo/my-class"=>["MyClass"],
                "foo/other-class"=>["OtherClass"]
            ])
        );
    }

}
