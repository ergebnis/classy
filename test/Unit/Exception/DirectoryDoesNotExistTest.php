<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2023 Andreas Möller
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

#[Framework\Attributes\CoversClass(Exception\DirectoryDoesNotExist::class)]
final class DirectoryDoesNotExistTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testDefaults(): void
    {
        $exception = new Exception\DirectoryDoesNotExist();

        self::assertSame('', $exception->directory());
    }

    public function testFromDirectoryReturnsException(): void
    {
        $directory = self::faker()->sentence();

        $exception = Exception\DirectoryDoesNotExist::fromDirectory($directory);

        $message = \sprintf(
            'Directory "%s" does not exist.',
            $directory,
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($directory, $exception->directory());
    }
}
