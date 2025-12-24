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
use Ergebnis\Classy\File;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Exception\FileCouldNotBeParsed
 *
 * @uses \Ergebnis\Classy\File
 */
final class FileCouldNotBeParsedTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromFileAndParseErrorReturnsException(): void
    {
        $faker = self::faker();

        $file = File::fromString(\sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->word(),
        ));
        $parseError = new \ParseError($faker->sentence());

        $exception = Exception\FileCouldNotBeParsed::fromFileAndParseError(
            $file,
            $parseError,
        );

        $message = \sprintf(
            'File "%s" could not be read.',
            $file->toString(),
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame($parseError, $exception->getPrevious());
    }
}
