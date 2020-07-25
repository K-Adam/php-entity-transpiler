<?php

namespace EntityTranspiler;

use EntityTranspiler\SourceExplorers\SourceExplorer;
use EntityTranspiler\Loaders\Loader;
use EntityTranspiler\Generators\Generator;
use EntityTranspiler\Utils\ParameterValidator;

class Framework {

    /** @var SourceExplorer */
    private $sourceExplorer;

    /** @var Loader */
    private $loader;

    /** @var Generator */
    private $generator;

    public function __construct(
        SourceExplorer $sourceExplorer,
        Loader $loader,
        Generator $generator
    ) {
        $this->sourceExplorer = $sourceExplorer;
        $this->loader = $loader;
        $this->generator = $generator;
    }

    public function execute() {

        $sources = $this->sourceExplorer->getSources();

        foreach($sources as $source) {
            $this->loader->processSource($source);
        }

        $entityCollection = $this->loader->flush();

        foreach($entityCollection->getEntities() as $entity) {
            $this->generator->processEntity($entity);
        }

        $outputCollection = $this->generator->generate();

        $outputs = $outputCollection->getOutputs();
        foreach($outputs as $output) {
            $output->write();
        }

    }

    public static function create(array $params): Framework {

        $validator = new ParameterValidator($params);

        $validator->assert("sourceExplorer", "array");
        $validator->assert("loader", "array");
        $validator->assert("generator", "array");

        $framework = new Framework(
            self::createItem($params["sourceExplorer"]),
            self::createItem($params["loader"]),
            self::createItem($params["generator"])
        );

        return $framework;

    }

    public static function createItem(array $params) {
        $validator = new ParameterValidator($params);
        $validator->assert("class", "string");
        $validator->assert("config", "array");

        $cName = $params["class"];

        return $cName::create($params["config"]);
    }

}
