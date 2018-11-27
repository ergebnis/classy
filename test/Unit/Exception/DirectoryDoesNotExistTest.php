<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Test\Unit\Exception;

use Localheinz\Classy\Exception\DirectoryDoesNotExist;

/**
 * @internal
 */
final class DirectoryDoesNotExistTest extends AbstractTestCase
{
    public function testFromDirectoryReturnsException(): void
    {
        $directory = $this->faker()->sentence;

        $exception = DirectoryDoesNotExist::fromDirectory($directory);

        self::assertInstanceOf(DirectoryDoesNotExist::class, $exception);

        $message = \sprintf(
            'Directory "%s" does not exist.',
            $directory
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($directory, $exception->directory());
    }
}
