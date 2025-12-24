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

use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Source;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Source
 *
 * @uses \Ergebnis\Classy\Exception\InvalidSource
 */
final class SourceTest extends Framework\TestCase
{
    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidSource::class);

        Source::fromString($value);
    }

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
