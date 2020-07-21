<?php

namespace EntityTranspiler\SourceExplorers;

use EntityTranspiler\SourceExplorers\SourceExplorer;
use EntityTranspiler\Sources\PhpClass;
use EntityTranspiler\Utils\ParameterValidator;

class ClassFinder implements SourceExplorer {

    /** @var string * */
    private $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath(): string {
        return $this->path;
    }

    /** @var PhpClass[] * */
    public function getSources(): array {
        $sources = [];

        $finder = new \Symfony\Component\Finder\Finder();
        $iter = new \hanneskod\classtools\Iterator\ClassIterator(
            $finder
                ->in($this->path)
                ->exclude('vendor')
                ->name("*.php")
        );

        foreach ($iter as $class) {
            $sources[] = new PhpClass($class->getName());
        }

        return $sources;
    }

    public static function create(array $params): ClassFinder {

        (new ParameterValidator($params))->assert("path", "string");

        return new ClassFinder($params["path"]);

    }

}
