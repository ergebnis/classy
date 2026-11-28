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
 * @covers \Ergebnis\Classy\FilePath
 *
 * @uses \Ergebnis\Classy\Exception\InvalidFilePath
 */
final class FilePathTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Classy\Exception\InvalidFilePath::class);

        Classy\FilePath::fromString($value);
    }

    public function testFromStringReturnsFilePath(): void
    {
        $value = self::faker()->word();

        $filePath = Classy\FilePath::fromString($value);

        self::assertSame($value, $filePath->toString());
    }
}
