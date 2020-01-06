# classy

[![Continuous Deployment](https://github.com/ergebnis/classy/workflows/Continuous%20Deployment/badge.svg)](https://github.com/ergebnis/classy/actions)
[![Continuous Integration](https://github.com/ergebnis/classy/workflows/Continuous%20Integration/badge.svg)](https://github.com/ergebnis/classy/actions)
[![Code Coverage](https://codecov.io/gh/ergebnis/classy/branch/master/graph/badge.svg)](https://codecov.io/gh/ergebnis/classy)
[![Type Coverage](https://shepherd.dev/github/ergebnis/classy/coverage.svg)](https://shepherd.dev/github/ergebnis/classy)
[![Latest Stable Version](https://poser.pugx.org/ergebnis/classy/v/stable)](https://packagist.org/packages/ergebnis/classy)
[![Total Downloads](https://poser.pugx.org/ergebnis/classy/downloads)](https://packagist.org/packages/ergebnis/classy)

Provides a finder for classy elements.

## Installation

Run

```
$ composer require ergebnis/classy
```


## Usage

### Collect classy constructs from source code

Use `Constructs::fromSource()` to collect classy constructs in source code:

```php
<?php

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Constructs;

$source = <<<'PHP'
<?php

namespace Example;

class Foo {}

interface Bar {}

trait Baz {}
PHP;

/** @var Construct[] $constructs */
$constructs = Constructs::fromSource($source);

$names = array_map(static function (Construct $construct): string {
    return $construct->name();
}, $constructs);

var_dump($names); // ['Example\Bar', 'Example\Baz', 'Example\Foo']
```

### Collect classy constructs from a directory

Use `Constructs::fromDirectory()` to collect classy constructs in a directory:

```php
<?php

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Constructs;

/** @var Construct[] $constructs */
$constructs = Constructs::fromDirectory(__DIR__ . '/example');

$names = array_map(static function (Construct $construct): string {
    return $construct->name();
}, $constructs);

var_dump($names); // ['Example\Bar', 'Example\Bar\Baz', 'Example\Foo\Bar\Baz']
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/master/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

## Credits

The algorithm for finding classes in PHP files in [`Constructs`](src/Constructs.php) has
been adopted from [`Zend\File\ClassFileLocator`](https://github.com/zendframework/zend-file/blob/release-2.7.1/src/ClassFileLocator.php) (originally licensed under BSD-3-Clause).
