<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

use Ergebnis\Classy;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Name
 */
final class NameTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testFromStringReturnsName(): void
    {
        $value = self::faker()->word();

        $name = Classy\Name::fromString($value);

        self::assertSame($value, $name->toString());
    }
}
