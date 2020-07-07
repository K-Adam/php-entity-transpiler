<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"ANNOTATION"})
 */
class Collection {

    private $type;

    public function __construct(array $values = []) {
        $this->type = $values["value"] ?? "mixed";
    }

    public function __toString(): string {
        $sType = $this->type;
        return "array<$sType>";
    }

}
