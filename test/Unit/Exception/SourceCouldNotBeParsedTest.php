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

namespace Ergebnis\Classy\Test\Unit\Exception;

use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Exception\SourceCouldNotBeParsed
 *
 * @uses \Ergebnis\Classy\FilePath
 */
final class SourceCouldNotBeParsedTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testWithParseErrorReturnsException(): void
    {
        $parseError = new \ParseError(self::faker()->sentence());

        $exception = Exception\SourceCouldNotBeParsed::withParseError($parseError);

        self::assertSame('Source could not be parsed.', $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($parseError, $exception->getPrevious());
    }
}
