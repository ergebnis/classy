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

final class Php81
{
    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function classyConstructs(): \Generator
    {
        $scenarios = [
            Test\Util\Scenario::create(
                'php81-within-namespace',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespace/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespace\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespace\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespace\Baz::class),
                    Type::trait(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespace\Qux::class),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespaceWithBraces/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Baz::class),
                    Type::trait(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Qux::class),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/Php81/WithinMultipleNamespaces/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Baz::class),
                    Type::trait(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Qux::class),
                    Type::enum(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Foo::class),
                    Type::class(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Bar::class),
                    Type::interface(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Baz::class),
                    Type::trait(),
                ),
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Qux::class),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespaceWithSingleSegment/source.php',
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
                ConstructFromSource::create(
                    Name::fromString('Ergebnis\\Qux'),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/Php81/WithMethodsNamedAfterKeywords/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithMethodsNamedAfterKeywords\Foo::class),
                    Type::class(),
                ),
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                'php81-with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/Php81/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                ConstructFromSource::create(
                    Name::fromString(Test\Fixture\Classy\Php81\WithMethodsNamedAfterKeywordsAndReturnType\Foo::class),
                    Type::class(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-without-namespace',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespace/source.php',
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
                ConstructFromSource::create(
                    Name::fromString('Qux'),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndMultiLineComments/source.php',
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
                ConstructFromSource::create(
                    Name::fromString('Qux'),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndShellStyleComments/source.php',
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
                ConstructFromSource::create(
                    Name::fromString('Qux'),
                    Type::enum(),
                ),
            ),
            Test\Util\Scenario::create(
                'php81-without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndSingleLineComments/source.php',
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
                ConstructFromSource::create(
                    Name::fromString('Qux'),
                    Type::enum(),
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
