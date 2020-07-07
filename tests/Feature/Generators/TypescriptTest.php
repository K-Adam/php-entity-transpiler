<?php

namespace Tests\Feature\Generators;

use Tests\TestCase;

use EntityTranspiler\Entity;
use EntityTranspiler\Properties\Property;
use EntityTranspiler\Properties\PhpType;
use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;
use EntityTranspiler\Utils\NameFormat\Writer as NameWriter;

use EntityTranspiler\Generators\Typescript as TypescriptGenerator;

class TypescriptTest extends TestCase {

    public function testSimpleCase() {

        $entity = new Entity("TestNamespace\\SimpleClass");

        $stringProperty = new Property("stringProperty", new PhpType(PhpType::TYPE_SCALAR, "string"));
        $intProperty = new Property("intProperty", new PhpType(PhpType::TYPE_SCALAR, "int"));

        $entity->properties[] = $stringProperty;
        $entity->properties[] = $intProperty;

        $generator = TypescriptGenerator::create([
            "classResolver" => [
                [
                    "source" => "*",
                    "pathResolver" => [
                        "type" => PathResolver::TYPE_DIRECTORY,
                        "path" => "test_outputs/typescript",
                        "dirNameFormat" => NameWriter::KEBAB_CASE,
                        "fileNameFormat" => NameWriter::KEBAB_CASE
                    ],
                    "classNameResolver" => ["format"=>NameWriter::CAMEL_CASE]
                ]
            ]
        ]);

        $generator->processEntity($entity);
        $collection = $generator->generate();

        $outputs = $collection->getOutputs();
        $this->assertEquals(1, count($outputs));

        $outFile = $outputs[0];
        $this->assertEquals("simple-class.ts", $outFile->getFileName());

        $expectedCode = "
export class SimpleClass {
  stringProperty: string;
  intProperty: number;
}
";

        $this->assertNormalizedTextEquals($expectedCode, $outFile->getContent());

    }

}
