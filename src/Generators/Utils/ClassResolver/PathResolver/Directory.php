<?php

namespace EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;

use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;
use EntityTranspiler\Utils\NameFormat\Parser;
use EntityTranspiler\Utils\NameFormat\Writer;
use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ParameterValidator;

class Directory extends PathResolver {
    
    private $path;
    private $dirNameFormat;
    private $fileNameFormat;
    
    // Additional parameters
    
    function __construct(string $path, string $dirNameFormat, string $fileNameFormat) {
        $this->path = $path;
        $this->dirNameFormat = $dirNameFormat;
        $this->fileNameFormat = $fileNameFormat;
    }

    public function resolve(ClassRef $ref): string {
        $parser = new Parser();
        $writer = new Writer();

        $fileName = $writer->write($this->fileNameFormat, $parser->parse($ref->getName()));
        
        $dirNameFormat = $this->dirNameFormat;
        $namespaceParts = array_map(function($part) use($parser, $writer, $dirNameFormat) {
            return $writer->write($dirNameFormat, $parser->parse($part));
        }, $ref->getNamespaceChain());
        
        return implode("/", array_merge([$this->path], $namespaceParts, [$fileName]));
    }
    
    public function getPath(): string {
        return $this->path;
    }

    public function getDirNameFormat(): string {
        return $this->dirNameFormat;
    }

    public function getFileNameFormat(): string {
        return $this->fileNameFormat;
    }
        
    public static function create(array $params): PathResolver {
        
        $validator = new ParameterValidator($params);
        
        $validator->assert("path", "string");
        $validator->assert("dirNameFormat", "string");
        $validator->assert("fileNameFormat", "string");
       
        return new Directory($params["path"], $params["dirNameFormat"], $params["fileNameFormat"]);
        
    }

}
