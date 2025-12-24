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
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\FilePath
 *
 * @uses \Ergebnis\Classy\Exception\InvalidFilePath
 */
final class FilePathTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidFilePath::class);

        FilePath::fromString($value);
    }

    public function testFromStringReturnsFilePath(): void
    {
        $value = self::faker()->word();

        $filePath = FilePath::fromString($value);

        self::assertSame($value, $filePath->toString());
    }
}
