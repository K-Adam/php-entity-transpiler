
# EntityTranspiler

EntityTranspiler converts your Php classes to classes for your frontend project, so you don't have to define them manually. For example it makes it possible to generate Typescript classes from Doctrine entities, but it works independently from any Php framework.

The project is still a work in progress. The api may change based on feedbacks and be simplified for easier usage. I aim to reach 100% test coverage before the `1.0` release.

## Installation

Using [Composer](http://getcomposer.org/):

```
composer require k-adam/entity-transpiler --dev
```
## Usage

Annotate your php classes and its properties, from which you would like to generate frontend class definitions:

```php
use EntityTranspiler\Annotations as ET;

/**
 * @ET\Entity
 */
class User {
    /**
     * @ET\Property(type="int")
     */
    private $id;

  	/**
     * @ET\Property(type="string")
  	 */
  	private $name;
}
```

Create a configuration file:

```php
<?php

use EntityTranspiler\Generators\Utils\ClassResolver\PathResolver;
use EntityTranspiler\Utils\ClassRef\Transformer;
use EntityTranspiler\Utils\NameFormat\Writer;

return [
    // Location of your php entity classes
    "sourceExplorer"=>[
        "class" => \EntityTranspiler\SourceExplorers\ClassFinder::class,
        "config" => ["path"=>"src"]
    ],
    // Use docblock annotations, to define entities and properties
    "loader"=>[
        "class" => \EntityTranspiler\Loaders\Annotation::class,
        "config" => []
    ],
    // Export options
    "generator"=>[
        // Generate typescript classes
        "class" => \EntityTranspiler\Generators\Typescript::class,
        "config" => [
            // Define associations between source namespace+class names, and the target namespace+class names
            // Multiple resolve rules can be defined, with different sources
            "classResolver" => [
                [
                    // Filter source namespace/class
                    // "source" => "App\\*"
                    "source" => "*",

                    // Output options
                    "pathResolver" => [
                        "type" => PathResolver::TYPE_DIRECTORY,
                        "path" => "output",
                        "dirNameFormat" => Writer::KEBAB_CASE,
                        "fileNameFormat" => Writer::KEBAB_CASE
                    ],

                    // Class/enum name format
                    "classNameResolver" => ["format"=>Writer::PASCAL_CASE],
                    "enumResolver" => ["propertyNameFormat"=>Writer::PASCAL_CASE],

                    // Transform namespace+class names before export
                    "transformer" => [
                        "type" => Transformer::TYPE_COMPOSITION,
                        "transformers" => [
                            [
                                // Skip top level ( App ) namespace in path
                                "type" => Transformer::TYPE_SLICE_NAMESPACE,
                                "offset" => 1
                            ],
                            [
                                // Keep the currently top level namespaces: Shop/Ticketing (offset:1)
                                // Concatenate the subnamespaces with the classnames ( Shop\Cart\Entry -> Shop\CartEntry )
                                "type" => Transformer::TYPE_PREPEND_NAMESPACE,
                                "offset" => 1
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]
];
```

Run entity transpiler:

```
vendor/bin/entity-transpiler --config=LOCATION_OF_YOUR_CONFIG_FILE
```

The result should look like this:

```typescript
export class User {
	id: number;
	name: string;
}
```

### Example project

For more advanced examples, see the [example project](https://github.com/K-Adam/php-entity-transpiler-examples)

## Todo

Some features are still missing from the project, but I plan to implement them in the future:

- Configurable Inheritance Mapping
- Interfaces
- Enum as object key
- Use covariant returns and contravariant parameters in the Php 7.4 release ( PathResolver / Transformer / etc... )
