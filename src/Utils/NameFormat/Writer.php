<?php

namespace EntityTranspiler\Utils\NameFormat;

class Writer {

    const CAMEL_CASE = 'CAMEL_CASE';
    const PASCAL_CASE = 'PASCAL_CASE';
    const SNAKE_CASE = 'SNAKE_CASE';
    const KEBAB_CASE = 'KEBAB_CASE';

    /**
     * @param mixed
     * @param string[]
     */
    public function write($type, array $nameParts): string {
        switch($type) {

            case self::CAMEL_CASE:
                return $this->getCamelCase($nameParts);

            case self::PASCAL_CASE:
                return $this->getPascalCase($nameParts);

            case self::SNAKE_CASE:
                return $this->getSnakeCase($nameParts);

            case self::KEBAB_CASE:
                return $this->getKebabCase($nameParts);

            default:
                throw new \Exception("Unknown type: $type");

        }
    }

    private function getCamelCase(array $nameParts): string {
        $first = array_shift($nameParts);
        $capParts = array_map('ucfirst', $nameParts);

        return $first . implode($capParts);
    }

    private function getPascalCase(array $nameParts): string {
        $capParts = array_map('ucfirst', $nameParts);

        return implode($capParts);
    }

    private function getSnakeCase(array $nameParts): string {
        return implode('_', $nameParts);
    }

    private function getKebabCase(array $nameParts): string {
        return implode('-', $nameParts);
    }

}
