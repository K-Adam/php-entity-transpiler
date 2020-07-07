<?php

namespace EntityTranspiler;

use EntityTranspiler\SourceExplorers\SourceExplorer;
use EntityTranspiler\Loaders\Loader;
use EntityTranspiler\Generators\Generator;
use EntityTranspiler\Utils\ParameterValidator;

class Framework {
    
    /** @var SourceExplorer */
    public $sourceExplorer;
    
    /** @var Loader */
    public $loader;
    
    /** @var Generator */
    public $generator;
    
    public function execute() {
        
        $sources = $this->sourceExplorer->getSources();
        
        foreach($sources as $source) {
            $this->loader->processSource($source);
        }
        
        $entityCollection = $this->loader->load();
        
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
        
        $framework = new Framework();
        $framework->sourceExplorer = self::createItem($params["sourceExplorer"]);
        $framework->loader = self::createItem($params["loader"]);
        $framework->generator = self::createItem($params["generator"]);
        
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
