<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\DataProvider;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Test;

final class Php74
{
    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function classyConstructs(): \Generator
    {
        $scenarios = [
            Test\Util\Scenario::create(
                'php74-within-namespace',
                __DIR__ . '/../Fixture/Classy/Php74/WithinNamespace/source.php',
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespace\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespace\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespace\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php74-within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/Php74/WithinNamespaceWithBraces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php74-within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/Php74/WithinMultipleNamespaces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php74-within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/Php74/WithinNamespaceWithSingleSegment/source.php',
                Construct::fromName('Ergebnis\\Bar'),
                Construct::fromName('Ergebnis\\Baz'),
                Construct::fromName('Ergebnis\\Foo'),
            ),
            Test\Util\Scenario::create(
                'php74-with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/Php74/WithMethodsNamedAfterKeywords/source.php',
                Construct::fromName(Test\Fixture\Classy\Php74\WithMethodsNamedAfterKeywords\Foo::class),
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                'php74-with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/Php74/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                Construct::fromName(Test\Fixture\Classy\Php74\WithMethodsNamedAfterKeywordsAndReturnType\Foo::class),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespace/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndMultiLineComments/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndShellStyleComments/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndSingleLineComments/source.php',
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
