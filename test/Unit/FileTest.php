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

use Ergebnis\Classy\File;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\File
 */
final class FileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromStringReturnsFile(): void
    {
        $value = self::faker()->word();

        $file = File::fromString($value);

        self::assertSame($value, $file->toString());
    }
}
