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

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Test;

final class Php80
{
    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function classyConstructs(): \Generator
    {
        $scenarios = [
            Test\Util\Scenario::create(
                'php80-within-namespace',
                __DIR__ . '/../Fixture/Classy/Php80/WithinNamespace/source.php',
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespace\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespace\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespace\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php80-within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/Php80/WithinNamespaceWithBraces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespaceWithBraces\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespaceWithBraces\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinNamespaceWithBraces\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php80-within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/Php80/WithinMultipleNamespaces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Bar\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Bar\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Bar\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Foo\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Foo\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php80\WithinMultipleNamespaces\Foo\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php80-within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/Php80/WithinNamespaceWithSingleSegment/source.php',
                Construct::fromName('Ergebnis\\Bar'),
                Construct::fromName('Ergebnis\\Baz'),
                Construct::fromName('Ergebnis\\Foo'),
            ),
            Test\Util\Scenario::create(
                'php80-with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/Php80/WithMethodsNamedAfterKeywords/source.php',
                Construct::fromName(Test\Fixture\Classy\Php80\WithMethodsNamedAfterKeywords\Foo::class),
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                'php80-with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/Php80/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                Construct::fromName(Test\Fixture\Classy\Php80\WithMethodsNamedAfterKeywordsAndReturnType\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php80-without-namespace',
                __DIR__ . '/../Fixture/Classy/Php80/WithoutNamespace/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php80-without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/Php80/WithoutNamespaceAndMultiLineComments/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php80-without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/Php80/WithoutNamespaceAndShellStyleComments/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php80-without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/Php80/WithoutNamespaceAndSingleLineComments/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
        ];

        foreach ($scenarios as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }
}
