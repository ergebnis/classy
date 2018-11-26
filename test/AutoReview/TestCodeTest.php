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

namespace Localheinz\Classy\Test\AutoReview;

use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 */
final class TestCodeTest extends Framework\TestCase
{
    use Helper;

    public function testTestClassesAreAbstractOrFinal(): void
    {
        $this->assertClassesAreAbstractOrFinal(__DIR__ . '/..', [
            'Bar',
            'Baz',
            'Baz\Bar\Foo\Bar',
            'Baz\Bar\Foo\Baz',
            'Baz\Bar\Foo\Foo',
            'Foo',
            'Foo\Bar\Baz\Bar',
            'Foo\Bar\Baz\Baz',
            'Foo\Bar\Baz\Foo',
            'stdClass',
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            'string',
        ]);
    }
}
