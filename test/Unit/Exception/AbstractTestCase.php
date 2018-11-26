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

use Localheinz\Classy\Exception\ExceptionInterface;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

abstract class AbstractTestCase extends Framework\TestCase
{
    use Helper;

    final public function testImplementsExceptionInterface(): void
    {
        $this->assertClassImplementsInterface(ExceptionInterface::class, $this->className());
    }

    final protected function className(): string
    {
        return \preg_replace(
            '/Test$/',
            '',
            \str_replace(
                'Localheinz\\Classy\\Test\\Unit\\',
                'Localheinz\\Classy\\',
                static::class
            )
        );
    }
}
