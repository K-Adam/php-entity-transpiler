<?php

namespace TestClasses\SimpleProject01;

use EntityTranspiler\Annotations as ET;

/**
  * @ET\Entity
  */
class MyClass01 {

  /**
    * @ET\Property(type="string")
    */
  public $testString;

  /**
    * @ET\Property(type="int")
    */
  private $testInt;

  protected $testSkip;

}
