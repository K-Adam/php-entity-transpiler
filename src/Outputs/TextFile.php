<?php

namespace EntityTranspiler\Outputs;

class TextFile extends Output {

    private $content;

    function __construct(string $path, string $content) {
        parent::__construct($path);

        $this->content = $content;
    }

    public function getContent() {
        return $this->content;
    }

    public function write() {
        $this->prepareOutput();
        file_put_contents($this->getPath(), $this->content);
    }

}
