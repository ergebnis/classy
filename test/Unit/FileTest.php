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
use Ergebnis\Classy\File;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\File
 *
 * @uses \Ergebnis\Classy\Exception\InvalidFile
 */
final class FileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    /**
     * @dataProvider \Ergebnis\DataProvider\StringProvider::blank
     * @dataProvider \Ergebnis\DataProvider\StringProvider::empty
     */
    public function testFromStringRejectsInvalidValue(string $value): void
    {
        $this->expectException(Exception\InvalidFile::class);

        File::fromString($value);
    }

    public function testFromStringReturnsFile(): void
    {
        $value = self::faker()->word();

        $file = File::fromString($value);

        self::assertSame($value, $file->toString());
    }
}
