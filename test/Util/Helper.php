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

namespace Ergebnis\Classy\Test\Util;

use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Filesystem;

trait Helper
{
    final protected static function faker(string $locale = 'en_US'): Generator
    {
        /**
         * @var array<string, Generator> $fakers
         */
        static $fakers = [];

        if (!\array_key_exists($locale, $fakers)) {
            $faker = Factory::create($locale);

            $faker->seed(9001);

            $fakers[$locale] = $faker;
        }

        return $fakers[$locale];
    }

    final protected static function filesystem(): Filesystem\Filesystem
    {
        return new Filesystem\Filesystem();
    }

    final protected static function temporaryDirectory(): string
    {
        return __DIR__ . '/../../.build/test';
    }

    final protected static function fileWithParseError(): string
    {
        return __DIR__ . '/../Fixture/ParseError/source.php';
    }
}
