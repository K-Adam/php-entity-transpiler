# EntityTranspiler

EntityTranspiler converts your Php classes to classes for your frontend project, so you don't have to define them manually. For example it makes it possible to generate Typescript classes from Doctrine entities, but it works independently from any Php framework.

The project is still a work in progress. The api may change based on feedbacks and be simplified for easier usage. I aim to reach 100% test coverage before the `1.0` release.

## Installation

Using [Composer](http://getcomposer.org/):

```
composer require k-adam/entity-transpiler --dev
```

## Usage example

[Example project](https://github.com/K-Adam/php-entity-transpiler-examples)

## Todo

Some features are still missing from the project, but I plan to implement them in the future:

- Configurable Inheritance Mapping
- Interfaces
- Enum as object key
- Use covariant returns and contravariant parameters in the Php 7.4 release ( PathResolver / Transformer / etc... )
