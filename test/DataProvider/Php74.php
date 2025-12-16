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

use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Test;
use Ergebnis\Classy\Type;

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
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Baz::class),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/Php74/WithinNamespaceWithBraces/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinNamespaceWithBraces\Baz::class),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/Php74/WithinMultipleNamespaces/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Foo\Baz::class),
                    Type::trait(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithinMultipleNamespaces\Bar\Baz::class),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/Php74/WithinNamespaceWithSingleSegment/source.php',
                ConstructFromSource::create(
                    Name::fromString('Ergebnis\\Foo'),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Ergebnis\\Bar'),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Ergebnis\\Baz'),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/Php74/WithMethodsNamedAfterKeywords/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithMethodsNamedAfterKeywords\Foo::class),
                    Type::class(),
                ),
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                'php74-with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/Php74/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php74\WithMethodsNamedAfterKeywordsAndReturnType\Foo::class),
                    Type::class(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespace/source.php',
                ConstructFromSource::create(
                    Name::fromString('Foo'),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Bar'),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Baz'),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndMultiLineComments/source.php',
                ConstructFromSource::create(
                    Name::fromString('Foo'),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Bar'),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Baz'),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndShellStyleComments/source.php',
                ConstructFromSource::create(
                    Name::fromString('Foo'),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Bar'),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Baz'),
                    Type::trait(),
                ),
            ),
            Test\Util\Scenario::create(
                'php74-without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/Php74/WithoutNamespaceAndSingleLineComments/source.php',
                ConstructFromSource::create(
                    Name::fromString('Foo'),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Bar'),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString('Baz'),
                    Type::trait(),
                ),
            ),
        ];

        foreach ($scenarios as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }
}
