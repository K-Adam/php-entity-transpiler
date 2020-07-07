<?php

namespace EntityTranspiler\Utils\NameFormat;

class Parser {

    /** @return string[] */
    public function parse(string $name): array {
        $parts = preg_split('/(?:(?<=[a-z])(?=[A-Z])|_)+/', $name);
        $lcasedParts = array_map('strtolower', $parts);

        return $lcasedParts;
    }

}
