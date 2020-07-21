<?php

namespace EntityTranspiler\Generators\Typescript;

use EntityTranspiler\Generators\Utils\RelativePathConverter;

class ImportPrinter {

    private $pathConverter;

    public function __construct(RelativePathConverter $pathConverter) {
        $this->pathConverter = $pathConverter;
    }

    public function getImportString(array $dependencies): string {
        $results = [];
        foreach($dependencies as $path => $classNames) {
            $results[] = $this->getImportLine($path, $classNames);
        }
        return implode("\n", $results);
    }

    public function getImportLine(string $path, array $classNames): string {
        $relativePath = $this->pathConverter->getPath($path);
        $classList = implode(", ", $classNames);

        return "import { $classList } from \"$relativePath\";";
    }

}
