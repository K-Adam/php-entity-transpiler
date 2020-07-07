<?php

namespace EntityTranspiler\Utils\ClassRef;

use EntityTranspiler\Utils\ClassRef;
use EntityTranspiler\Utils\ParameterValidator;
use EntityTranspiler\Utils\Exceptions\InvalidParameterValue;

abstract class Transformer {

    const TYPE_COMPOSITION = 'COMPOSITION';
    const TYPE_FLATTEN = 'FLATTEN';
    const TYPE_IDENTITY = 'IDENTITY';
    const TYPE_PREPEND_NAMESPACE = 'PREPEND_NAMESPACE';
    const TYPE_SLICE_NAMESPACE = 'SLICE_NAMESPACE';

    public abstract function transform(ClassRef $ref): ClassRef;

    public static function create(array $params): Transformer {

        (new ParameterValidator($params))->assert("type", "string");

        $type = $params["type"];
        switch($type) {

            case self::TYPE_COMPOSITION:
                return Transformer\Composition::create($params);

            case self::TYPE_FLATTEN:
                return Transformer\Flatten::create($params);

            case self::TYPE_IDENTITY:
                return Transformer\Identity::create($params);

            case self::TYPE_PREPEND_NAMESPACE:
                return Transformer\PrependNamespace::create($params);

            case self::TYPE_SLICE_NAMESPACE:
                return Transformer\SliceNamespace::create($params);

            default:
                throw new InvalidParameterValue($params, $type);
        }

    }

}
