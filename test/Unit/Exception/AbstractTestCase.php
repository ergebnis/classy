<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Localheinz\Classy\Test\Unit\Exception;

use Localheinz\Classy\Exception\ExceptionInterface;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 */
abstract class AbstractTestCase extends Framework\TestCase
{
    use Helper;

    final public function testImplementsExceptionInterface(): void
    {
        $this->assertClassImplementsInterface(ExceptionInterface::class, $this->className());
    }

    final protected function className(): string
    {
        $className = \preg_replace(
            '/Test$/',
            '',
            \str_replace(
                'Localheinz\\Classy\\Test\\Unit\\',
                'Localheinz\\Classy\\',
                static::class
            )
        );

        if (!\is_string($className)) {
            throw new \RuntimeException(\sprintf(
                'Could not resolve class name from test class name "%s".',
                static::class
            ));
        }

        return $className;
    }
}
