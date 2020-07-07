<?php

namespace EntityTranspiler\Generators;

use EntityTranspiler\Entity;
use EntityTranspiler\Outputs\OutputCollection;
use EntityTranspiler\Outputs\TextFile;
use EntityTranspiler\Utils\Indentation;

use EntityTranspiler\Generators\Utils\DependencyCollector;
use EntityTranspiler\Generators\Utils\ImportBuilder;
use EntityTranspiler\Generators\Utils\RelativePathConverter;

use EntityTranspiler\Generators\Typescript\ClassPrinter;
use EntityTranspiler\Generators\Typescript\ImportPrinter;
use EntityTranspiler\Generators\Utils\ClassResolver;

use EntityTranspiler\Utils\ParameterValidator;

class Typescript implements Generator {

    /** @var Indentation */
    public $indentation;

    /** @var ClassResolver */
    public $classResolver;

    /** @var DependencyCollector */
    private $dependencyCollector;

    /** @var array */
    private $results = [];

    function __construct() {

        $this->indentation = self::getDefaultIndent();
        $this->classResolver = new ClassResolver();
        $this->dependencyCollector = new DependencyCollector();

    }

    public function processEntity(Entity $entity) {
        $fPath = $this->classResolver->resolvePath($entity->getClassRef());

        $dependencies = $this->dependencyCollector->collectEntityDependencies($entity);

        $importBuilder = new ImportBuilder($this->classResolver);
        foreach($dependencies as $dependency) {
            $importBuilder->addDependencyClass($dependency);
        }

        $importData = $importBuilder->build();

        $cPrinter = new ClassPrinter($this->indentation);
        $cPrinter->classResolver = $this->classResolver;

        $cRule = $this->classResolver->findRule($entity->getClassRef());
        if($cRule) {
            if($cRule->transformer) {
                $cPrinter->transformer = $cRule->transformer;
            }
            if($cRule->enumNameFormat) {
                $cPrinter->enumNameFormat = $cRule->enumNameFormat;
            }
        }

        $cString = $cPrinter->getClassString($entity);

        $contents = "export $cString";

        $this->results[$fPath]["imports"][] = $importData;
        $this->results[$fPath]["classes"][] = $contents;

    }

    public function generate(): OutputCollection {
        $collection = new OutputCollection();

        foreach($this->results as $fPath => $result) {
            $imports = $this->dependencyCollector->mergeDependencies($result["imports"]);

            $content = "";
            $separator = "\n\n";

            if(count($imports) > 0) {
                $importPrinter = new ImportPrinter(RelativePathConverter::fromFilePath($fPath));
                $importBlock = $importPrinter->getImportString($imports);

                $content = $importBlock.$separator;
            }

            $content .= implode($separator, $result["classes"]);

            $collection->add(new TextFile($fPath.".ts", $content));
        }

        $this->results = [];

        return $collection;
    }

    private static function getDefaultIndent(): Indentation {
        return new Indentation(Indentation::TYPE_SPACE, 2);
    }

    public static function create(array $params): Typescript {

        $validator = new ParameterValidator($params);

        $validator->assert("classResolver", "array");
        $validator->assertType("indentation", "array");

        $generator = new Typescript();

        $generator->classResolver = ClassResolver::create($params["classResolver"]);

        if(isset($params["indentation"])) {
            $generator->indentation = Indentation::create($params["indentation"]);
        }

        return $generator;

    }

}
