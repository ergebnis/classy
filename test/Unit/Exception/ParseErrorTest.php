<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Test\Unit\Exception;

use Localheinz\Classy\Exception\ParseError;

final class ParseErrorTest extends AbstractTestCase
{
    public function testExtendsParseError()
    {
        $this->assertClassExtends(\ParseError::class, ParseError::class);
    }

    public function testFromParseErrorReturnsException()
    {
        $parseError = new \ParseError($this->faker()->sentence());

        $exception = ParseError::fromParseError($parseError);

        $this->assertInstanceOf(ParseError::class, $exception);
        $this->assertSame($parseError->getMessage(), $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($parseError, $exception->getPrevious());
    }

    public function testFromFileNameAndParseErrorReturnsException()
    {
        $fileName = __FILE__;
        $parseError = new \ParseError($this->faker()->sentence());

        $exception = ParseError::fromFileNameAndParseError(
            $fileName,
            $parseError
        );

        $this->assertInstanceOf(ParseError::class, $exception);

        $expectedMessage = \sprintf(
            'A parse error occurred when parsing "%s": "%s".',
            $fileName,
            $parseError->getMessage()
        );

        $this->assertSame($expectedMessage, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($parseError, $exception->getPrevious());
    }
}
