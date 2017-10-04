<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Test\Unit;

use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

final class ProjectCodeTest extends Framework\TestCase
{
    use Helper;

    public function testProductionClassesAreAbstractOrFinal()
    {
        $this->assertClassesAreAbstractOrFinal(__DIR__ . '/../../src');
    }

    public function testProductionClassesHaveTests()
    {
        $this->assertClassesHaveTests(
            __DIR__ . '/../../src',
            'Localheinz\\Classy\\',
            'Localheinz\\Classy\\Test\\Unit\\'
        );
    }

    public function testTestClassesAreAbstractOrFinal()
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
             * @link https://github.com/zendframework/zend-file/pull/41
             */
            'string',
        ]);
    }
}
