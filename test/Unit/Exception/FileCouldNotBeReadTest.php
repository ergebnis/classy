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

namespace Ergebnis\Classy\Test\Unit\Exception;

use Ergebnis\Classy;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Exception\FileCouldNotBeRead
 *
 * @uses \Ergebnis\Classy\FilePath
 */
final class FileCouldNotBeReadTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testAtFilePathReturnsException(): void
    {
        $faker = self::faker();

        $filePath = Classy\FilePath::fromString(\sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->word(),
        ));

        $exception = Classy\Exception\FileCouldNotBeRead::atFilePath($filePath);

        $message = \sprintf(
            'File "%s" could not be read.',
            $filePath->toString(),
        );

        self::assertSame($message, $exception->getMessage());
    }
}
