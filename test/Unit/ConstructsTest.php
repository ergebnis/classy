<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Test\Unit;

use Localheinz\Classy\Construct;
use Localheinz\Classy\Constructs;
use Localheinz\Classy\Exception;
use Localheinz\Classy\Test\Fixture;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Localheinz\Classy\Constructs
 *
 * @uses \Localheinz\Classy\Construct
 * @uses \Localheinz\Classy\Exception\DirectoryDoesNotExist
 * @uses \Localheinz\Classy\Exception\MultipleDefinitionsFound
 * @uses \Localheinz\Classy\Exception\ParseError
 */
final class ConstructsTest extends Framework\TestCase
{
    /**
     * @var string
     */
    private $fileWithParseError = __DIR__ . '/../Fixture/ParseError/MessedUp.php';

    protected function setUp(): void
    {
        \file_put_contents($this->fileWithParseError, self::sourceTriggeringParseError());
    }

    protected function tearDown(): void
    {
        \unlink($this->fileWithParseError);
    }

    public function testFromSourceThrowsParseErrorIfParseErrorIsThrownDuringParsing(): void
    {
        $source = self::sourceTriggeringParseError();

        $this->expectException(Exception\ParseError::class);

        Constructs::fromSource($source);
    }

    /**
     * @dataProvider provideSourceWithoutClassyConstructs
     *
     * @param string $source
     */
    public function testFromSourceReturnsEmptyArrayIfNoClassyConstructsHaveBeenFound(string $source): void
    {
        self::assertEquals([], Constructs::fromSource($source));
    }

    public function provideSourceWithoutClassyConstructs(): \Generator
    {
        foreach ($this->casesWithoutClassyConstructs() as $key => $fileName) {
            yield $key => [
                \file_get_contents($fileName),
            ];
        }
    }

    /**
     * @dataProvider provideSourceWithClassyConstructs
     *
     * @param string   $source
     * @param string[] $constructs
     */
    public function testFromSourceReturnsArrayOfClassyConstructsSortedByName(string $source, array $constructs): void
    {
        self::assertEquals($constructs, Constructs::fromSource($source));
    }

    public function provideSourceWithClassyConstructs(): \Generator
    {
        foreach ($this->casesWithClassyConstructs() as $key => [$fileName, $names]) {
            \sort($names);

            yield $key => [
                \file_get_contents($fileName),
                \array_map(static function (string $name): Construct {
                    return Construct::fromName($name);
                }, $names),
            ];
        }
    }

    public function testFromDirectoryThrowsDirectoryDoesNotExistIfDirectoryDoesNotExist(): void
    {
        $this->expectException(Exception\DirectoryDoesNotExist::class);

        Constructs::fromDirectory(__DIR__ . '/NonExistent');
    }

    public function testFromDirectoryThrowsParseErrorIfParseErrorIsThrownDuringParsing(): void
    {
        $this->expectException(Exception\ParseError::class);

        Constructs::fromDirectory(__DIR__ . '/../Fixture/ParseError');
    }

    /**
     * @dataProvider provideDirectoryWithoutClassyConstructs
     *
     * @param string $directory
     */
    public function testFromDirectoryReturnsEmptyArrayIfNoClassyConstructsHaveBeenFound(string $directory): void
    {
        self::assertCount(0, Constructs::fromDirectory($directory));
    }

    public function provideDirectoryWithoutClassyConstructs(): \Generator
    {
        foreach ($this->casesWithoutClassyConstructs() as $key => $fileName) {
            yield $key => [
                \dirname($fileName),
            ];
        }
    }

    /**
     * @dataProvider provideDirectoryWithClassyConstructs
     *
     * @param string   $directory
     * @param string[] $classyConstructs
     */
    public function testFromDirectoryReturnsArrayOfClassyConstructsSortedByName(string $directory, array $classyConstructs = []): void
    {
        self::assertEquals($classyConstructs, Constructs::fromDirectory($directory));
    }

    public function provideDirectoryWithClassyConstructs(): \Generator
    {
        foreach ($this->casesWithClassyConstructs() as $key => [$fileName, $names]) {
            \sort($names);

            yield $key => [
                \dirname($fileName),
                \array_map(static function (string $name) use ($fileName): Construct {
                    return Construct::fromName($name)->definedIn(self::realPath($fileName));
                }, $names),
            ];
        }
    }

    public function testFromDirectoryTraversesDirectoriesAndReturnsArrayOfClassyConstructsSortedByName(): void
    {
        $classyConstructs = [
            Construct::fromName(Fixture\Traversal\Foo::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo.php')),
            Construct::fromName(Fixture\Traversal\Foo\Bar::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Bar.php')),
            Construct::fromName(Fixture\Traversal\Foo\Baz::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Baz.php')),
        ];

        self::assertEquals($classyConstructs, Constructs::fromDirectory(__DIR__ . '/../Fixture/Traversal'));
    }

    public function testFromDirectoryThrowsMultipleDefinitionsFoundIfMultipleDefinitionsOfSameConstructHaveBeenFound(): void
    {
        $this->expectException(Exception\MultipleDefinitionsFound::class);

        Constructs::fromDirectory(__DIR__ . '/../Fixture/MultipleDefinitions');
    }

    private function casesWithoutClassyConstructs(): array
    {
        return [
            'no-php-file' => __DIR__ . '/../Fixture/NoClassy/NoPhpFile/source.md',
            'with-anonymous-class' => __DIR__ . '/../Fixture/NoClassy/WithAnonymousClass/source.php',
            'with-anonymous-class-and-multi-line-comments' => __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndMultiLineComments/source.php',
            'with-anonymous-class-and-shell-style-comments' => __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndShellStyleComments/source.php',
            'with-anonymous-class-and-single-line-comments' => __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndSingleLineComments/source.php',
            'with-class-keyword' => __DIR__ . '/../Fixture/NoClassy/WithClassKeyword/source.php',
            'with-nothing' => __DIR__ . '/../Fixture/NoClassy/WithNothing/source.php',
        ];
    }

    private function casesWithClassyConstructs(): array
    {
        return [
            'within-namespace' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespace/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'within-namespace-and-shell-style-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndShellStyleComments/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'within-namespace-and-single-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndSingleLineComments/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'within-namespace-and-multi-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndMultiLineComments/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'within-namespace-with-braces' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceWithBraces/source.php',
                [
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'within-multiple-namespaces-with-braces' => [
                __DIR__ . '/../Fixture/Classy/WithinMultipleNamespaces/source.php',
                [
                    'Baz\\Bar\\Foo\\Bar',
                    'Baz\\Bar\\Foo\\Baz',
                    'Baz\\Bar\\Foo\\Foo',
                    'Foo\\Bar\\Baz\\Bar',
                    'Foo\\Bar\\Baz\\Baz',
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'with-methods-named-after-keywords' => [
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywords/source.php',
                [
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            'with-methods-named-after-keywords-and-return-type' => [
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                [
                    'Foo\\Bar\\Baz\\Foo',
                ],
            ],
            'without-namespace' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespace/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'without-namespace-and-multi-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndMultiLineComments/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'without-namespace-and-shell-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndShellStyleComments/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
            'without-namespace-and-single-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndSingleLineComments/source.php',
                [
                    'Bar',
                    'Baz',
                    'Foo',
                ],
            ],
        ];
    }

    private static function realPath(string $path): string
    {
        $resolved = \realpath($path);

        if (!\is_string($resolved)) {
            throw new \RuntimeException(\sprintf(
                'Failed resolving the real path of "%s".',
                $path
            ));
        }

        return $resolved;
    }

    private static function sourceTriggeringParseError(): string
    {
        return <<<'TXT'
<?php

final class MessedUp
{
TXT;
    }
}
