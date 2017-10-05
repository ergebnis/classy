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

use Localheinz\Classy\Construct;
use Localheinz\Classy\Constructs;
use Localheinz\Classy\Exception;
use Localheinz\Classy\Test\Fixture;
use PHPUnit\Framework;

final class ConstructsTest extends Framework\TestCase
{
    /**
     * @dataProvider providerSourceAndClassyConstructs
     *
     * @param string   $source
     * @param string[] $constructs
     */
    public function testFromSourceReturnsArrayOfClassyConstructsSortedByName(string $source, array $constructs)
    {
        $this->assertEquals($constructs, Constructs::fromSource($source));
    }

    public function providerSourceAndClassyConstructs(): \Generator
    {
        foreach ($this->cases() as $key => list($fileName, $names)) {
            \sort($names);

            yield $key => [
                \file_get_contents($fileName),
                \array_map(function (string $name) {
                    return Construct::fromName($name);
                }, $names),
            ];
        }
    }

    public function testFromDirectoryThrowsDirectoryDoesNotExistIfDirectoryDoesNotExist()
    {
        $directory = __DIR__ . '/NonExistent';

        $this->expectException(Exception\DirectoryDoesNotExist::class);

        Constructs::fromDirectory($directory);
    }

    /**
     * @dataProvider providerDirectoryAndClassyConstructs
     *
     * @param string   $directory
     * @param string[] $classyConstructs
     */
    public function testFromDirectoryReturnsArrayOfClassyConstructsSortedByName(string $directory, array $classyConstructs = [])
    {
        $this->assertEquals($classyConstructs, Constructs::fromDirectory($directory));
    }

    public function providerDirectoryAndClassyConstructs(): \Generator
    {
        foreach ($this->cases() as $key => list($fileName, $names)) {
            \sort($names);

            yield $key => [
                \dirname($fileName),
                \array_map(function (string $name) use ($fileName) {
                    return Construct::fromName($name)->definedIn(\realpath($fileName));
                }, $names),
            ];
        }
    }

    public function testFromDirectoryIgnoresNonPhpFiles()
    {
        $directory = __DIR__ . '/../Fixture/NoPhpFiles';

        $this->assertCount(0, Constructs::fromDirectory($directory));
    }

    public function testFromDirectoryTraversesDirectoriesAndReturnsArrayOfClassyConstructsSortedByName()
    {
        $directory = __DIR__ . '/../Fixture/Traversal';

        $classyConstructs = [
            Construct::fromName(Fixture\Traversal\Foo::class)->definedIn(\realpath($directory . '/Foo.php')),
            Construct::fromName(Fixture\Traversal\Foo\Bar::class)->definedIn(\realpath($directory . '/Foo/Bar.php')),
            Construct::fromName(Fixture\Traversal\Foo\Baz::class)->definedIn(\realpath($directory . '/Foo/Baz.php')),
        ];

        $this->assertEquals($classyConstructs, Constructs::fromDirectory($directory));
    }

    public function testFromDirectoryThrowsMultipleDefinitionsFoundIfMultipleDefinitionsOfSameConstructHaveBeenFound()
    {
        $directory = __DIR__ . '/../Fixture/MultipleDefinitions';

        $this->expectException(Exception\MultipleDefinitionsFound::class);

        Constructs::fromDirectory($directory);
    }

    private function cases(): array
    {
        return [
            'no-classy' => [
                __DIR__ . '/../Fixture/NoClassy/source.php',
                [],
            ],
            'no-classy-with-class-keyword' => [
                __DIR__ . '/../Fixture/NoClassyWithClassKeyword/source.php',
                [],
            ],
            'no-classy-with-anonymous-class' => [
                __DIR__ . '/../Fixture/NoClassyWithAnonymousClass/source.php',
                [],
            ],
            'no-classy-with-anonymous-class-and-multi-line-comments' => [
                __DIR__ . '/../Fixture/NoClassyWithAnonymousClassAndMultiLineComments/source.php',
                [],
            ],
            'no-classy-with-anonymous-class-and-single-line-comments' => [
                __DIR__ . '/../Fixture/NoClassyWithAnonymousClassAndSingleLineComments/source.php',
                [],
            ],
            'no-classy-with-anonymous-class-and-shell-style-comments' => [
                __DIR__ . '/../Fixture/NoClassyWithAnonymousClassAndShellStyleComments/source.php',
                [],
            ],
            'classy-without-namespace' => [
                __DIR__ . '/../Fixture/ClassyWithoutNamespace/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'classy-without-namespace-and-multi-line-comments' => [
                __DIR__ . '/../Fixture/ClassyWithoutNamespaceAndMultiLineComments/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'classy-without-namespace-and-single-line-comments' => [
                __DIR__ . '/../Fixture/ClassyWithoutNamespaceAndSingleLineComments/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'classy-within-namespace' => [
                __DIR__ . '/../Fixture/ClassyWithinNamespace/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'classy-within-namespace-with-braces' => [
                __DIR__ . '/../Fixture/ClassyWithinNamespaceWithBraces/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'classy-within-multiple-namespaces-with-braces' => [
                __DIR__ . '/../Fixture/ClassyWithinMultipleNamespaces/source.php',
                [
                    'Baz\\Bar\\Foo\\Bar',
                    'Baz\\Bar\\Foo\\Baz',
                    'Baz\\Bar\\Foo\\Foo',
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'classy-with-methods-named-after-keywords' => [
                __DIR__ . '/../Fixture/ClassyWithMethodsNamedAfterKeywords/source.php',
                [
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            /**
             * @link https://github.com/zendframework/zend-file/pull/41
             */
            'classy-with-methods-named-after-keywords-and-return-type' => [
                __DIR__ . '/../Fixture/ClassyWithMethodsNamedAfterKeywordsAndReturnType/source.php',
                [
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
        ];
    }
}
