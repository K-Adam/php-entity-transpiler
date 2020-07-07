<?php

namespace EntityTranspiler\Sources;

abstract class Source {
  
  public abstract function acceptVisitor(SourceVisitor $visitor);
  
}