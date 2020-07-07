<?php

namespace EntityTranspiler\Loaders;

use EntityTranspiler\EntityCollection;
use EntityTranspiler\Sources\Source;

interface Loader {
  
    function processSource(Source $source);
    function load(): EntityCollection;
  
}