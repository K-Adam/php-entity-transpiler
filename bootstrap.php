<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$anRoot = __DIR__."/src/Annotations/";

AnnotationRegistry::registerFile($anRoot."Entity.php");
AnnotationRegistry::registerFile($anRoot."Property.php");
AnnotationRegistry::registerFile($anRoot."Collection.php");
AnnotationRegistry::registerFile($anRoot."Map.php");
AnnotationRegistry::registerFile($anRoot."OverrideEnumValues.php");
