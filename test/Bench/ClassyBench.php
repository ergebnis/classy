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

namespace Ergebnis\Classy\Test\Bench;

use Ergebnis\Classy\Constructs;
use Zend\File;

final class ClassyBench
{
    /**
     * @Revs(10)
     */
    public function benchBaseline(): void
    {
        \array_reduce(
            \iterator_to_array(new File\ClassFileLocator($this->directory())),
            static function (array $classes, File\PhpClassFile $file): array {
                return \array_merge(
                    $classes,
                    $file->getClasses()
                );
            },
            []
        );
    }

    /**
     * @Revs(10)
     */
    public function benchConstructsFromDirectory(): void
    {
        Constructs::fromDirectory($this->directory());
    }

    private function directory(): string
    {
        return __DIR__ . '/../../vendor/phpunit/phpunit/src';
    }
}
