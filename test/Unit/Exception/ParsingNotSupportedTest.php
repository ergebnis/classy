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
 * @covers \Ergebnis\Classy\Exception\ParsingNotSupported
 */
final class ParsingNotSupportedTest extends Framework\TestCase
{
    public function testPhp80RequiredReturnsException(): void
    {
        $exception = Exception\ParsingNotSupported::php80Required();

        self::assertSame('Parsing requires PHP 8.0.', $exception->getMessage());
    }
}
