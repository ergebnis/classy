<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit\Exception;

use Ergebnis\Classy\Exception\ExceptionInterface;
use Ergebnis\Classy\Exception\ParseError;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Classy\Exception\ParseError
 */
final class ParseErrorTest extends Framework\TestCase
{
    use Helper;

    public function testFromParseErrorReturnsException(): void
    {
        $parseError = new \ParseError($this->faker()->sentence());

        $exception = ParseError::fromParseError($parseError);

        self::assertInstanceOf(ParseError::class, $exception);
        self::assertInstanceOf(\ParseError::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);
        self::assertSame($parseError->getMessage(), $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($parseError, $exception->getPrevious());
    }

    public function testFromFileNameAndParseErrorReturnsException(): void
    {
        $fileName = __FILE__;
        $parseError = new \ParseError($this->faker()->sentence());

        $exception = ParseError::fromFileNameAndParseError(
            $fileName,
            $parseError
        );

        self::assertInstanceOf(ParseError::class, $exception);
        self::assertInstanceOf(\ParseError::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);

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
