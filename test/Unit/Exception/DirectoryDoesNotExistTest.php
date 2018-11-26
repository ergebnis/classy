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

final class DirectoryDoesNotExistTest extends AbstractTestCase
{
    public function testFromDirectoryReturnsException(): void
    {
        $directory = $this->faker()->sentence;

        $exception = DirectoryDoesNotExist::fromDirectory($directory);

        $this->assertInstanceOf(DirectoryDoesNotExist::class, $exception);

        $message = \sprintf(
            'Directory "%s" does not exist.',
            $directory
        );

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($directory, $exception->directory());
    }
}
