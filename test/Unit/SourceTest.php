<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

use Ergebnis\Classy\Source;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Source
 *
 * @uses \Ergebnis\Classy\Exception\InvalidSource
 */
final class SourceTest extends Framework\TestCase
{
    public function testFromStringReturnsSource(): void
    {
        $value = <<<'PHP'
<?php

namespace Example;

class Foo {}

enum Bar {}

interface Baz {}

trait Qux {}
PHP;

        $source = Source::fromString($value);

        self::assertSame($value, $source->toString());
    }
}
