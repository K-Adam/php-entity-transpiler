<?php

namespace EntityTranspiler\Outputs;

abstract class Output {

    private $path;

    function __construct(string $path) {
        $this->path = $path;
    }

    public function getPath() {
        return $this->path;
    }

    public function getFileName() {
        return basename($this->path);
    }

    public function getDirName(): string {
        $parts = preg_split('/[\\\\\/]/', $this->path);
        array_pop($parts);

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    protected function prepareOutput() {
        $dirName = $this->getDirName();
    		if(!is_dir($dirName)) {
    			   mkdir($dirName, 0777, true);
    		}
    }

    public abstract function write();

}
