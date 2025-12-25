<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\DataProvider;

use Ergebnis\Classy\Test;

final class Any
{
    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function blankOrEmpty(): iterable
    {
        $scenariosWithoutClassyConstructs = [
            Test\Util\Scenario::create(
                'blank-file',
                __DIR__ . '/../Fixture/NoClassy/NoPhpFile/blank.txt',
            ),
            Test\Util\Scenario::create(
                'empty-file',
                __DIR__ . '/../Fixture/NoClassy/NoPhpFile/empty.txt',
            ),
        ];

        foreach ($scenariosWithoutClassyConstructs as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }

    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function noClassyConstructs(): iterable
    {
        $scenariosWithoutClassyConstructs = [
            Test\Util\Scenario::create(
                'no-php-file',
                __DIR__ . '/../Fixture/NoClassy/NoPhpFile/source.md',
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClass/source.php',
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-multi-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndMultiLineComments/source.php',
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-shell-style-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndShellStyleComments/source.php',
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-single-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndSingleLineComments/source.php',
            ),
            Test\Util\Scenario::create(
                'with-class-keyword',
                __DIR__ . '/../Fixture/NoClassy/WithClassKeyword/source.php',
            ),
            Test\Util\Scenario::create(
                'with-nothing',
                __DIR__ . '/../Fixture/NoClassy/WithNothing/source.php',
            ),
        ];

        foreach ($scenariosWithoutClassyConstructs as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }
}
