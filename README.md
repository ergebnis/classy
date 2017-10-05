# classy

[![Build Status](https://travis-ci.org/localheinz/classy.svg?branch=master)](https://travis-ci.org/localheinz/classy)
[![Code Climate](https://codeclimate.com/github/localheinz/classy/badges/gpa.svg)](https://codeclimate.com/github/localheinz/classy)
[![Test Coverage](https://codeclimate.com/github/localheinz/classy/badges/coverage.svg)](https://codeclimate.com/github/localheinz/classy/coverage)
[![Issue Count](https://codeclimate.com/github/localheinz/classy/badges/issue_count.svg)](https://codeclimate.com/github/localheinz/classy)
[![Latest Stable Version](https://poser.pugx.org/localheinz/classy/v/stable)](https://packagist.org/packages/localheinz/classy)
[![Total Downloads](https://poser.pugx.org/localheinz/classy/downloads)](https://packagist.org/packages/localheinz/classy)

Provides a finder for classy elements.

## Installation

Run

```
$ composer require localheinz/classy
```


## Usage

### Collect classy constructs from source code

Use `Constructs::fromSource()` to collect classy constructs in source code:

```php
use Localheinz\Classy\Construct;
use Localheinz\Classy\Constructs;

$source = <<<'PHP'
<?php

namespace Example;

class Foo {}

interface Bar {}

trait Baz {}
PHP;

/** @var Construct[] $constructs */
$constructs = Constructs::fromSource($source);

$names = array_map(function (Construct $construct) {
    return $construct->name();
}, $constructs);

var_dump($names); // ['Example\Bar', 'Example\Baz', 'Example\Foo']
```

### Collect classy constructs from a directory

Use `Constructs::fromDirectory()` to collect classy constructs in a directory:

```php
use Localheinz\Classy\Construct;
use Localheinz\Classy\Constructs;

/** @var Construct[] $constructs */
$constructs = Constructs::fromDirectory(__DIR__ . '/example');

$names = array_map(function (Construct $construct) {
    return $construct->name();
}, $constructs);

var_dump($names); // ['Example\Bar', 'Example\Bar\Baz', 'Example\Foo\Bar\Baz']
```

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](.github/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.
