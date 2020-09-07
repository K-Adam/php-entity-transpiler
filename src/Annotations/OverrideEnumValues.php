<?php

namespace EntityTranspiler\Annotations;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class OverrideEnumValues {

    private $values;

    public function __construct(array $values = []) {
        $this->values = $values["value"] ?? [];
    }

    public function getValues() {
        return $this->values;
    }

}
