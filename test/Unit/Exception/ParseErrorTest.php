<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Andreas Möller
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
 * @internal
 *
 * @covers \Ergebnis\Classy\Exception\ParseError
 */
final class ParseErrorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromParseErrorReturnsException(): void
    {
        $parseError = new \ParseError(self::faker()->sentence());

        $exception = Exception\ParseError::fromParseError($parseError);

        self::assertSame($parseError->getMessage(), $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($parseError, $exception->getPrevious());
    }

    public function testFromFileNameAndParseErrorReturnsException(): void
    {
        $fileName = __FILE__;
        $parseError = new \ParseError(self::faker()->sentence());

        $exception = Exception\ParseError::fromFileNameAndParseError(
            $fileName,
            $parseError
        );

        $expectedMessage = \sprintf(
            'A parse error occurred when parsing "%s": "%s".',
            $fileName,
            $parseError->getMessage()
        );

        self::assertSame($expectedMessage, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($parseError, $exception->getPrevious());
    }
}
