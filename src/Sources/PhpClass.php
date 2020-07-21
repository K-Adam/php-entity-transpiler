<?php

namespace EntityTranspiler\Sources;

use ReflectionClass;

class PhpClass extends Source {

  /** @var string **/
  private $className;

  function __construct($className) {
      $this->className = $className;
  }

  // TODO: Remove?
  public function getClassName(): string {
      return $this->className;
  }

  public function getReflectionClass(): ReflectionClass {
      return new ReflectionClass($this->className);
  }

  //

  public function acceptVisitor(SourceVisitor $visitor) {
      $visitor->visitPhpClass($this);
  }

}
