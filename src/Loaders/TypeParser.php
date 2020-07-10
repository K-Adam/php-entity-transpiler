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
          preg_match('/^(.+)\[\s*\]$/', $typeName, $match)
        ) {
            $subType = $this->parse($match[1]);
            return new PhpType(PhpType::TYPE_ARRAY, $subType);
        }

        return null;
    }

    private function parseObject(string $typeName) :? PhpType {

        $keyTypeExp = "int|string";
        $keyNameExp = "[a-zA-Z_]+";

        $regex = '/^{(?:\s*(?:('.$keyTypeExp.')|\[\s*('.$keyNameExp.')\s*:\s*('.$keyTypeExp.')\s*\])\s*:)?\s*(.*[^\s]+)\s*}$/';
        $match = [];

        preg_match($regex, $typeName, $match);

        if(!$match) return null;

        if($match[1]) {
            $keyType = $match[1];
            $keyName = "key";
        } elseif($match[3]) {
            $keyType = $match[3];
            $keyName = $match[2];
        } else {
            $keyType = "string";
            $keyName = "key";
        }

        if($match[4]) {
          $valueType = $this->parse($match[4]);
        } else {
          return null;
        }

        return new PhpType(PhpType::TYPE_OBJECT, $valueType, $keyType, $keyName);

    }

    private function parseClass(string $typeName) : PhpType {
        return new PhpType(PhpType::TYPE_CLASS, $typeName);
    }

}
