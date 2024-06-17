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

namespace Ergebnis\Classy\Test\Unit;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Constructs;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Constructs::class)]
#[Framework\Attributes\UsesClass(Construct::class)]
#[Framework\Attributes\UsesClass(Exception\DirectoryDoesNotExist::class)]
#[Framework\Attributes\UsesClass(Exception\MultipleDefinitionsFound::class)]
#[Framework\Attributes\UsesClass(Exception\ParseError::class)]
final class ConstructsTest extends Framework\TestCase
{
    private string $fileWithParseError = __DIR__ . '/../Fixture/ParseError/MessedUp.php';

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

    #[Framework\Attributes\DataProvider('provideScenarioWithoutClassyConstructs')]
    public function testFromSourceReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        self::assertSame([], $constructs);
    }

    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function provideScenarioWithoutClassyConstructs(): \Generator
    {
        $scenariosWithoutClassyConstructs = [
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'no-php-file',
                __DIR__ . '/../Fixture/NoClassy/NoPhpFile/source.md',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'with-anonymous-class',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClass/source.php',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'with-anonymous-class-and-multi-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndMultiLineComments/source.php',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'with-anonymous-class-and-shell-style-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndShellStyleComments/source.php',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'with-anonymous-class-and-single-line-comments',
                __DIR__ . '/../Fixture/NoClassy/WithAnonymousClassAndSingleLineComments/source.php',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
                'with-class-keyword',
                __DIR__ . '/../Fixture/NoClassy/WithClassKeyword/source.php',
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(70400),
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

    #[Framework\Attributes\DataProvider('provideScenarioWithClassyConstructsOnPhp81')]
    public function testFromSourceReturnsListOfClassyConstructs(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        $expected = \array_map(static function (Construct $construct): Construct {
            return Construct::fromName($construct->name());
        }, $scenario->constructsSortedByName());

        self::assertIsList($constructs);
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

    #[Framework\Attributes\DataProvider('provideScenarioWithoutClassyConstructs')]
    public function testFromDirectoryReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertSame([], $constructs);
    }

    #[Framework\Attributes\DataProvider('provideScenarioWithClassyConstructsOnPhp81')]
    public function testFromDirectoryReturnsListOfClassyConstructs(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertIsList($constructs);
        self::assertEquals($scenario->constructsSortedByName(), $constructs);
    }

    /**
     * @return \Generator<string, array{0: Test\Util\Scenario}>
     */
    public static function provideScenarioWithClassyConstructsOnPhp81(): \Generator
    {
        $scenarios = [
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-within-namespace',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespace/source.php',
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespace\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespace\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespace\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespace\Qux::class),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-within-namespace-with-braces',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespaceWithBraces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinNamespaceWithBraces\Qux::class),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-within-multiple-namespaces-with-braces',
                __DIR__ . '/../Fixture/Classy/Php81/WithinMultipleNamespaces/source.php',
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Bar\Qux::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Bar::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Baz::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Foo::class),
                Construct::fromName(Test\Fixture\Classy\Php81\WithinMultipleNamespaces\Foo\Qux::class),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-within-namespace-with-single-segment',
                __DIR__ . '/../Fixture/Classy/Php81/WithinNamespaceWithSingleSegment/source.php',
                Construct::fromName('Ergebnis\\Bar'),
                Construct::fromName('Ergebnis\\Baz'),
                Construct::fromName('Ergebnis\\Foo'),
                Construct::fromName('Ergebnis\\Qux'),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-with-methods-named-after-keywords',
                __DIR__ . '/../Fixture/Classy/Php81/WithMethodsNamedAfterKeywords/source.php',
                Construct::fromName(Test\Fixture\Classy\Php81\WithMethodsNamedAfterKeywords\Foo::class),
            ),
            /**
             * @see https://github.com/zendframework/zend-file/pull/41
             */
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-with-methods-named-after-keywords-and-return-type',
                __DIR__ . '/../Fixture/Classy/Php81/WithMethodsNamedAfterKeywordsAndReturnType/source.php',
                Construct::fromName(Test\Fixture\Classy\Php81\WithMethodsNamedAfterKeywordsAndReturnType\Foo::class),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-without-namespace',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespace/source.php',
                Construct::fromName('Bar'),
                Construct::fromName('Baz'),
                Construct::fromName('Foo'),
                Construct::fromName('Qux'),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-without-namespace-and-multi-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndMultiLineComments/source.php',
                Construct::fromName('Quux'),
                Construct::fromName('Quuz'),
                Construct::fromName('Qux'),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-without-namespace-and-shell-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndShellStyleComments/source.php',
                Construct::fromName('Corge'),
                Construct::fromName('Garply'),
                Construct::fromName('Grault'),
            ),
            Test\Util\Scenario::create(
                Test\Util\PhpVersion::fromInt(80100),
                'php81-without-namespace-and-single-line-comments',
                __DIR__ . '/../Fixture/Classy/Php81/WithoutNamespaceAndSingleLineComments/source.php',
                Construct::fromName('Fred'),
                Construct::fromName('Plugh'),
                Construct::fromName('Waldo'),
            ),
        ];

        foreach ($scenarios as $scenario) {
            yield $scenario->description() => [
                $scenario,
            ];
        }
    }

    public function testFromDirectoryTraversesDirectoriesAndReturnsListOfClassyConstructsSortedByName(): void
    {
        $expected = [
            Construct::fromName(Test\Fixture\Traversal\Foo::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo.php')),
            Construct::fromName(Test\Fixture\Traversal\Foo\Bar::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Bar.php')),
            Construct::fromName(Test\Fixture\Traversal\Foo\Baz::class)->definedIn(self::realPath(__DIR__ . '/../Fixture/Traversal/Foo/Baz.php')),
        ];

        $constructs = Constructs::fromDirectory(__DIR__ . '/../Fixture/Traversal');

        self::assertIsList($constructs);
        self::assertEquals($expected, $constructs);
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
                $path,
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
