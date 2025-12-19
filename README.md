# classy

[![Integrate](https://github.com/ergebnis/classy/workflows/Integrate/badge.svg)](https://github.com/ergebnis/classy/actions)
[![Merge](https://github.com/ergebnis/classy/workflows/Merge/badge.svg)](https://github.com/ergebnis/classy/actions)
[![Release](https://github.com/ergebnis/classy/workflows/Release/badge.svg)](https://github.com/ergebnis/classy/actions)
[![Renew](https://github.com/ergebnis/classy/workflows/Renew/badge.svg)](https://github.com/ergebnis/classy/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/classy/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/classy)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/classy/v/stable)](https://packagist.org/packages/ergebnis/classy)
[![Total Downloads](https://poser.pugx.org/ergebnis/classy/downloads)](https://packagist.org/packages/ergebnis/classy)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/classy/d/monthly)](https://packagist.org/packages/ergebnis/classy)

This project provides a [`composer`](https://getcomposer.org) package with a finder for classy constructs ([classes](https://www.php.net/manual/en/language.oop5.php), [enums](https://www.php.net/manual/en/language.types.enumerations.php), [interfaces](https://www.php.net/manual/en/language.oop5.interfaces.php), and [traits](https://www.php.net/manual/en/language.oop5.traits.php)).

## Installation

Run

```sh
composer require ergebnis/classy
```

## Usage

This package provides the following collectors for finding classy constructs:

- [`Ergebnis\Classy\Collector\TokenGetAllConstructFromSourceCollector`](https://github.com/ergebnis/phpstan-rules#collectortokengetallconstructfromsourcecollector)
- [`Ergebnis\Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector`](https://github.com/ergebnis/phpstan-rules#collectorphptokenizeconstructfromsourcecollector)
- [`Ergebnis\Classy\Collector\DefaultConstructFromFileCollector`](https://github.com/ergebnis/phpstan-rules#collectordefaultconstructfromfilecollector)
- [`Ergebnis\Classy\Collector\DefaultConstructFromSplFileInfoCollector`](https://github.com/ergebnis/phpstan-rules#collectordefaultconstructfromsplfileinfocollector)
- [`Ergebnis\Classy\Collector\DefaultConstructFromFinderCollector`](https://github.com/ergebnis/phpstan-rules#collectordefaultconstructfromfindercollector)

### `Collector\TokenGetAllConstructFromSourceCollector`

Use `Classy\Collector\TokenGetAllConstructFromSourceCollector` to collect classy constructs from source code on PHP 7:

```php
<?php

declare(strict_types=1);

use Ergebnis\Classy;

$source = <<<'PHP'
<?php

namespace Example;

class Foo {}

enum Bar {}

interface Baz {}

trait Qux {}
PHP;

$collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

$constructs = $collector->collectFromSource($source);

foreach ($constructs as $construct) {
    /** @var Classy\Construct $construct */
    echo sprintf(
        "- %s (%s)\n",
        $construct->name()->toString(),
        $construct->type()->toString(),
    );
}

// - Example\Foo (class)
// - Example\Bar (enum)
// - Example\Baz (interface)
// - Example\Qux (trait)
```

### `Collector\PhpTokenTokenizeConstructFromSourceCollector`

Use `Collector\PhpTokenTokenizeConstructFromSourceCollector` to collect classy constructs from source code on PHP 8:

```php
<?php

declare(strict_types=1);

use Ergebnis\Classy;

$source = <<<'PHP'
<?php

namespace Example;

class Foo {}

enum Bar {}

interface Baz {}

trait Qux {}
PHP;

$collector = new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();

$constructs = $collector->collectFromSource($source);

foreach ($constructs as $construct) {
    /** @var Classy\Construct $construct */
    echo sprintf(
        "- %s (%s)\n",
        $construct->name()->toString(),
        $construct->type()->toString(),
    );
}

// - Example\Foo (class)
// - Example\Bar (enum)
// - Example\Baz (interface)
// - Example\Qux (trait)
```

### `Collector\DefaultConstructFromFileCollector`

Use `Collector\DefaultConstructFromFileCollector` to collect classy constructs from a file:

```php
<?php

declare(strict_types=1);

use Ergebnis\Classy;

$file = __DIR__ . '/example.php';

$collector = new Classy\Collector\DefaultConstructFromFileCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

$constructs = $collector->collectFromFile($file);

foreach ($constructs as $construct) {
    /** @var Classy\Construct $construct */
    echo sprintf(
        "- %s (%s)\n",
        $construct->name()->toString(),
        $construct->type()->toString(),
    );
}

// - Example\Foo (class)
// - Example\Bar (enum)
// - Example\Baz (interface)
// - Example\Qux (trait)
```

### `Collector\DefaultConstructFromSplFileInfoCollector`

Use `Collector\DefaultConstructFromSplFileInfoCollector` to collect classy constructs from an instance of [`SplFileInfo`](https://www.php.net/manual/en/class.splfileinfo.php):

```php
<?php

declare(strict_types=1);

use Ergebnis\Classy;

$splFileInfo = new \SplFileInfo(__DIR__ . '/example.php');

$collector = new Classy\Collector\DefaultConstructFromSplFileInfoCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

$constructs = $collector->collectFromSplFileInfo($splFileInfo);

foreach ($constructs as $construct) {
    /** @var Classy\Construct $construct */
    echo sprintf(
        "- %s (%s)\n",
        $construct->name()->toString(),
        $construct->type()->toString(),
    );
}

// - Example\Foo (class)
// - Example\Bar (enum)
// - Example\Baz (interface)
// - Example\Qux (trait)
```

### `Collector\DefaultConstructFromFinderCollector`

Use `Collector\DefaultConstructFromFinderCollector` to collect classy constructs from an iterable of `SplFileInfo`:

```php
<?php

declare(strict_types=1);

use Ergebnis\Classy;
use Symfony\Component\Finder;

$finder = Finder\Finder::create()
  ->files()
  ->in(__DIR__ . '/src');

$collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector()));

$constructs = $collector->collectFromFinder($finder);

foreach ($constructs as $construct) {
    /** @var Classy\Construct $construct */
    echo sprintf(
        "- %s (%s)\n",
        $construct->name()->toString(),
        $construct->type()->toString(),
    );
}

// - Example\Foo (class)
// - Example\Bar (enum)
// - Example\Baz (interface)
// - Example\Qux (trait)
```

## Changelog

The maintainers of this project record notable changes to this project in a [changelog](CHANGELOG.md).

## Contributing

The maintainers of this project suggest following the [contribution guide](.github/CONTRIBUTING.md).

## Code of Conduct

The maintainers of this project ask contributors to follow the [code of conduct](https://github.com/ergebnis/.github/blob/main/CODE_OF_CONDUCT.md).

## General Support Policy

The maintainers of this project provide limited support.

You can support the maintenance of this project by [sponsoring @ergebnis](https://github.com/sponsors/ergebnis).

## PHP Version Support Policy

This project supports PHP versions with [active and security support](https://www.php.net/supported-versions.php).

The maintainers of this project add support for a PHP version following its initial release and drop support for a PHP version when it has reached the end of security support.

## Security Policy

This project has a [security policy](.github/SECURITY.md).

## License

This project uses the [MIT license](LICENSE.md).

## Credits

The algorithm for finding classes in PHP files in [`Constructs`](src/Constructs.php) has been adopted from [`Zend\File\ClassFileLocator`](https://github.com/zendframework/zend-file/blob/release-2.7.1/src/ClassFileLocator.php) (originally licensed under BSD-3-Clause).

## Social

Follow [@localheinz](https://twitter.com/intent/follow?screen_name=localheinz) and [@ergebnis](https://twitter.com/intent/follow?screen_name=ergebnis) on Twitter.
