<?php

namespace EntityTranspiler\Outputs;

class OutputCollection {
    
    /** @var Output[] */
    private $outputs = [];

    public function add(Output $item) {
        $this->outputs[] = $item;
    }

    /** @return Output[] */
    public function getOutputs() {
        return $this->outputs;
    }

}
