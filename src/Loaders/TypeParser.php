<?php

namespace EntityTranspiler\Loaders;

use EntityTranspiler\Properties\PhpType;

class TypeParser {

    public function parse(string $typeName): PhpType {

        if(strlen($typeName) == 0) throw new \Exception("Type name is empty");

        return
            $this->parseMixed($typeName) ??
            $this->parseScalar($typeName) ??
            $this->parseArray($typeName) ??
            $this->parseObject($typeName) ??
            $this->parseClass($typeName)
        ;
    }

    private function parseMixed(string $typeName) :? PhpType {
        if($typeName == 'mixed') {
            return new PhpType(PhpType::TYPE_MIXED);
        }

        return null;
    }

    private function parseScalar(string $typeName) :? PhpType {
        $scalars = ['string', 'int', 'float'];
        if(in_array($typeName, $scalars)) {
            return new PhpType(PhpType::TYPE_SCALAR, $typeName);
        }

        return null;
    }

    private function parseArray(string $typeName) :? PhpType {
        if($typeName == "array") {
            return new PhpType(PhpType::TYPE_ARRAY, new PhpType(PhpType::TYPE_MIXED));
        }

        $match = [];
        if(
          preg_match('/^array<\s*(.*)\s*>$/', $typeName, $match) ||
          preg_match('/(.+)\[\s*\]/', $typeName, $match)
        ) {
            $subType = $this->parse($match[1]);
            return new PhpType(PhpType::TYPE_ARRAY, $subType);
        }

        return null;
    }

    private function parseObject(string $typeName) :? PhpType {
        $first = substr($typeName, 0, 1);
        $last = substr($typeName, -1);

        if($first != '{' || $last != '}') {
            return null;
        }

        $content = trim(substr($typeName, 1, -1));
        $colonPos = strpos($content, ":");

        if ($colonPos === false) {
            return new PhpType(PhpType::TYPE_OBJECT, $this->parse($content), "string");
        } else {
            $keyType = rtrim(substr($content, 0, $colonPos));
            $valType = ltrim(substr($content, $colonPos+1));

            if(!in_array($keyType, ["string", "int"])) {
                throw new \Exception("Only string or int object key types are supported! Provided: $keyType");
            }

            return new PhpType(PhpType::TYPE_OBJECT, $this->parse($valType), $keyType);
        }
    }

    private function parseClass(string $typeName) : PhpType {
        return new PhpType(PhpType::TYPE_CLASS, $typeName);
    }

}
