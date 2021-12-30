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
use Ergebnis\Classy\Test;
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

    public function testFromSourceThrowsParseErrorWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = self::sourceTriggeringParseError();

        $this->expectException(Exception\ParseError::class);

        Constructs::fromSource($source);
    }

    /**
     * @dataProvider provideScenarioWithoutClassyConstructs
     */
    public function testFromSourceReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        self::assertSame([], $constructs);
    }

    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public function provideScenarioWithoutClassyConstructs(): \Generator
    {
        $scenariosWithoutClassyConstructs = [
            Test\Util\Scenario::create(
                'no-php-file',
                __DIR__ . '/../Fixture/NoClassy/NoPhpFile/source.md'
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClass/source.php'
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-multi-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndMultiLineComments/source.php'
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-shell-style-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndShellStyleComments/source.php'
            ),
            Test\Util\Scenario::create(
                'with-anonymous-class-and-single-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndSingleLineComments/source.php'
            ),
            Test\Util\Scenario::create(
                'with-class-keyword',
                __DIR__ . '/../Fixture/NoClassy/WithClassKeyword/source.php'
            ),
            Test\Util\Scenario::create(
                'with-nothing',
                __DIR__ . '/../Fixture/NoClassy/WithNothing/source.php'
            ),
        ];

        foreach ($scenariosWithoutClassyConstructs as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }

    /**
     * @dataProvider provideScenarioWithClassyConstructs
     */
    public function testFromSourceReturnsArrayOfClassyConstructsWithoutFileNamesWhenClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        $expected = \array_map(static function (Construct $construct): Construct {
            return Construct::fromName($construct->name());
        }, $scenario->constructsSortedByName());

        self::assertEquals($expected, $constructs);
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
     * @dataProvider provideScenarioWithoutClassyConstructs
     */
    public function testFromDirectoryReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertSame([], $constructs);
    }

    /**
     * @dataProvider provideScenarioWithClassyConstructs
     */
    public function testFromDirectoryReturnsArrayOfClassyConstructsSortedByNameWhenClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertEquals($scenario->constructsSortedByName(), $constructs);
    }

    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public function provideScenarioWithClassyConstructs(): \Generator
    {
        $scenariosWithClassyConstructs = [
            Test\Util\Scenario::create(
                'within-namespace',
                __DIR__ . '/../Fixture/Classy/WithinNamespace/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespace\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-namespace-and-shell-style-comments',
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndShellStyleComments/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndShellStyleComments\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndSingleLineComments/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndSingleLineComments\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/WithinNamespaceAndMultiLineComments/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceAndMultiLineComments\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/WithinNamespaceWithBraces/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinNamespaceWithBraces\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/WithinMultipleNamespaces/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Bar\\Foo'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Bar'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Baz'),
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithinMultipleNamespaces\\Foo\\Foo')
            ),
            Test\Util\Scenario::create(
                'within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/WithinNamespaceWithSingleSegment/source.php',
                Construct::fromName('Ergebnis\\Bar'),
                Construct::fromName('Ergebnis\\Baz'),
                Construct::fromName('Ergebnis\\Foo')
            ),
            Test\Util\Scenario::create(
                'with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywords/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithMethodsNamedAfterKeywords\\Foo')
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                'with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                Construct::fromName('Ergebnis\\Classy\\Test\\Fixture\\Classy\\WithMethodsNamedAfterKeywordsAndReturnType\\Foo')
            ),
            Test\Util\Scenario::create(
                'without-namespace',
                __DIR__ . '/../Fixture/Classy/WithoutNamespace/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo')
            ),
            Test\Util\Scenario::create(
                'without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndMultiLineComments/source.php',
                Construct::fromName('Quux'),
                Construct::fromName('Quuz'),
                Construct::fromName('Qux')
            ),
            Test\Util\Scenario::create(
                'without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndShellStyleComments/source.php',
                Construct::fromName('Corge'),
                Construct::fromName('Garply'),
                Construct::fromName('Grault')
            ),
            Test\Util\Scenario::create(
                'without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/WithoutNamespaceAndSingleLineComments/source.php',
                Construct::fromName('Fred'),
                Construct::fromName('Plugh'),
                Construct::fromName('Waldo')
            ),
        ];

        foreach ($scenariosWithClassyConstructs as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }

    public function testFromDirectoryTraversesDirectoriesAndReturnsArrayOfClassyConstructsSortedByName(): void
    {
        $classyConstructs = [
            Construct::fromName(Test\Fixture\Traversal\Foo::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo.php')),
            Construct::fromName(Test\Fixture\Traversal\Foo\Bar::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Bar.php')),
            Construct::fromName(Test\Fixture\Traversal\Foo\Baz::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Baz.php')),
        ];

        self::assertEquals($classyConstructs, Constructs::fromDirectory(__DIR__ . '/../Fixture/Traversal'));
    }

    public function testFromDirectoryThrowsMultipleDefinitionsFoundIfMultipleDefinitionsOfSameConstructHaveBeenFound(): void
    {
        $this->expectException(Exception\MultipleDefinitionsFound::class);

        Constructs::fromDirectory(__DIR__ . '/../Fixture/MultipleDefinitions');
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
