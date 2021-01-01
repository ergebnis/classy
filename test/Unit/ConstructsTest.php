<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Constructs;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test\Fixture;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Classy\Constructs
 *
 * @uses \Ergebnis\Classy\Construct
 * @uses \Ergebnis\Classy\Exception\DirectoryDoesNotExist
 * @uses \Ergebnis\Classy\Exception\MultipleDefinitionsFound
 * @uses \Ergebnis\Classy\Exception\ParseError
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
     */
    public function testFromSourceReturnsEmptyArrayIfNoClassyConstructsHaveBeenFound(string $source): void
    {
        self::assertEquals([], Constructs::fromSource($source));
    }

    /**
     * @return \Generator<array<string>>
     */
    public function provideSourceWithoutClassyConstructs(): \Generator
    {
        foreach ($this->casesWithoutClassyConstructs() as $key => $fileName) {
            $source = \file_get_contents($fileName);

            if (!\is_string($source)) {
                throw new \RuntimeException(\sprintf(
                    'File "%s" should exist and be readable.',
                    $fileName
                ));
            }

            yield $key => [
                $source,
            ];
        }
    }

    /**
     * @dataProvider provideSourceWithClassyConstructs
     *
     * @param string[] $constructs
     */
    public function testFromSourceReturnsArrayOfClassyConstructsSortedByName(string $source, array $constructs): void
    {
        self::assertEquals($constructs, Constructs::fromSource($source));
    }

    /**
     * @return \Generator<array{0: string, 1: Construct[]}>
     */
    public function provideSourceWithClassyConstructs(): \Generator
    {
        foreach ($this->casesWithClassyConstructs() as $key => [$fileName, $names]) {
            \sort($names);

            $source = \file_get_contents($fileName);

            if (!\is_string($source)) {
                throw new \RuntimeException(\sprintf(
                    'File "%s" should exist and be readable.',
                    $fileName
                ));
            }

            yield $key => [
                $source,
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
     */
    public function testFromDirectoryReturnsEmptyArrayIfNoClassyConstructsHaveBeenFound(string $directory): void
    {
        self::assertCount(0, Constructs::fromDirectory($directory));
    }

    /**
     * @return \Generator<array<string>>
     */
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
     * @param string[] $classyConstructs
     */
    public function testFromDirectoryReturnsArrayOfClassyConstructsSortedByName(string $directory, array $classyConstructs = []): void
    {
        self::assertEquals($classyConstructs, Constructs::fromDirectory($directory));
    }

    /**
     * @return \Generator<array{0: string, 1:Construct[]}>
     */
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

    /**
     * @return array<string, string>
     */
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

    /**
     * @return array<string, array{0: string, 1: string[]}>
     */
    private function casesWithClassyConstructs(): array
    {
        return [
            'within-namespace' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespace/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Foo',
                ],
            ],
            'within-namespace-and-shell-style-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndShellStyleComments/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Foo',
                ],
            ],
            'within-namespace-and-single-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndSingleLineComments/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Foo',
                ],
            ],
            'within-namespace-and-multi-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndMultiLineComments/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Foo',
                ],
            ],
            'within-namespace-with-braces' => [
                __DIR__ . '/../Fixture/Classy/WithinNamespaceWithBraces/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Foo',
                ],
            ],
            'within-multiple-namespaces-with-braces' => [
                __DIR__ . '/../Fixture/Classy/WithinMultipleNamespaces/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Foo',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Bar',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Baz',
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Foo',
                ],
            ],
            'with-methods-named-after-keywords' => [
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywords/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithMethodsNamedAfterKeywords\\Foo',
                ],
            ],
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            'with-methods-named-after-keywords-and-return-type' => [
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                [
                    'Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithMethodsNamedAfterKeywordsAndReturnType\\Foo',
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
                    'Qux',
                    'Quux',
                    'Quuz',
                ],
            ],
            'without-namespace-and-shell-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndShellStyleComments/source.php',
                [
                    'Corge',
                    'Garply',
                    'Grault',
                ],
            ],
            'without-namespace-and-single-line-comments' => [
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndSingleLineComments/source.php',
                [
                    'Fred',
                    'Plugh',
                    'Waldo',
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
