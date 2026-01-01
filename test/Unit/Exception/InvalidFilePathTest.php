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

use Ergebnis\Classy\Exception;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Exception\InvalidFilePath
 */
final class InvalidFilePathTest extends Framework\TestCase
{
    public function testBlankOrEmptyReturnsException(): void
    {
        $exception = Exception\InvalidFilePath::blankOrEmpty();

        self::assertSame('File path can not be blank or empty.', $exception->getMessage());
    }
}
