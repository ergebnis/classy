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

namespace Localheinz\Classy\Test\Bench;

use Localheinz\Classy\Constructs;
use Zend\File;

final class ClassyBench
{
    /**
     * @Revs(10)
     */
    public function benchBaseline()
    {
        \iterator_to_array(new File\ClassFileLocator($this->directory()));
    }

    /**
     * @Revs(10)
     */
    public function benchConstructsFromDirectory()
    {
        Constructs::fromDirectory($this->directory());
    }

    private function directory(): string
    {
        return __DIR__ . '/../../vendor/phpunit/phpunit/src';
    }
}
