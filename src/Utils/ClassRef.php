<?php

namespace EntityTranspiler\Utils;

class ClassRef {

    /** @var string */
    private $fullName;

    /** @var string[] */
    private $namespaceChain;

    /** @var string */
    private $name;

    public function __construct(string $fullName) {
        $this->fullName = $fullName;

        $nameParts = explode('\\', $fullName);

        $this->name = array_pop($nameParts);
        $this->namespaceChain = $nameParts;
    }

    /** @return string */
    public function getFullName(): string {
        return $this->fullName;
    }

    /** @return string */
    public function getName(): string {
        return $this->name;
    }

    /** @return string[] */
    public function getNamespaceChain(): array {
        return $this->namespaceChain;
    }

    public function isBuiltIn(): bool {
        return in_array($this->getFullName(), ["DateTime"]);
    }

    public function __toString(): string {
        return $this->getFullName();
    }

    public static function fromParsed(array $namespaceChain, string $name): ClassRef {
        $fName = implode("\\", array_merge($namespaceChain, [$name]));
        return new ClassRef($fName);
    }

}
