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
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Exception\FileCouldNotBeParsed
 *
 * @uses \Ergebnis\Classy\FilePath
 */
final class FileCouldNotBeParsedTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testAtFilePathWithParseErrorReturnsException(): void
    {
        $faker = self::faker();

        $filePath = FilePath::fromString(\sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->word(),
        ));
        $parseError = new \ParseError($faker->sentence());

        $exception = Exception\FileCouldNotBeParsed::atFilePathWithParseError(
            $filePath,
            $parseError,
        );

        $message = \sprintf(
            'File "%s" could not be parsed because of a parse error with message "%s".',
            $filePath->toString(),
            $parseError->getMessage(),
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($parseError, $exception->getPrevious());
    }
}
