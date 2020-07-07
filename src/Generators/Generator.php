<?php

namespace EntityTranspiler\Generators;

use EntityTranspiler\Entity;
use EntityTranspiler\Outputs\OutputCollection;

interface Generator {

    function processEntity(Entity $entity);
    function generate(): OutputCollection;

}
