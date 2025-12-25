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
 * @covers \Ergebnis\Classy\Exception\FileDoesNotExist
 *
 * @uses \Ergebnis\Classy\FilePath
 */
final class FileDoesNotExistTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testAtFilePathReturnsException(): void
    {
        $faker = self::faker();

        $filePath = FilePath::fromString(\sprintf(
            '%s.%s',
            $faker->slug(),
            $faker->word(),
        ));

        $exception = Exception\FileDoesNotExist::atFilePath($filePath);

        $message = \sprintf(
            'File "%s" does not exist.',
            $filePath->toString(),
        );

        self::assertSame($message, $exception->getMessage());
    }
}
