<?php

namespace EntityTranspiler\Generators\Utils;

class RelativePathConverter {

    private $path;
    private $relativeDotStart;
    private $removeExtension;

    function __construct(string $directoryAbsolutePath, bool $relativeDotStart = true, bool $removeExtension = false) {
        $this->path = $directoryAbsolutePath;
        $this->relativeDotStart = $relativeDotStart;
        $this->removeExtension = $removeExtension;
    }

    public function getPath(string $absolutePath): string {

        if($this->removeExtension) {
            $absolutePath = preg_replace('/\.[^\.]+$/', '', $absolutePath);
        }

        if($this->path == "") return ($this->relativeDotStart ? "./" : "").$absolutePath;

        $pathParts = explode('/', $this->path);
        $paramParts = explode('/', $absolutePath);

        $paramFile = array_pop($paramParts);

        while( count($pathParts)>0 && count($paramParts)>0 && $pathParts[0] == $paramParts[0] ) {
            array_shift($pathParts);
            array_shift($paramParts);
        }

        if(count($pathParts) == 0) {
            return ($this->relativeDotStart ? "./" : "").implode('/', array_merge($paramParts, [$paramFile]));
        }

        return str_repeat("../", count($pathParts)).implode('/', array_merge($paramParts, [$paramFile]));

    }

    public static function fromFilePath(string $filePath, bool $relativeDotStart = true, bool $removeExtension = false): RelativePathConverter {
        $parts = explode('/', $filePath);
        array_pop($parts);

        $dirPath = implode('/', $parts);

        return new RelativePathConverter($dirPath, $relativeDotStart, $removeExtension);
    }

}
