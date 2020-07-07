<?php

namespace EntityTranspiler\Sources;

class PhpClass extends Source {
  
  /** @var string **/
  private $className;
  
  function __construct($className) {
    $this->className = $className;
  }
  
  public function getClassName(): string {
    return $this->className;
  }
  
  //
  
  public function acceptVisitor(SourceVisitor $visitor) {
    $visitor->visitPhpClass($this);
  }
  
}