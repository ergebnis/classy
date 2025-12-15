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

namespace Ergebnis\Classy\Test\Unit;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Constructs;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Constructs
 *
 * @uses \Ergebnis\Classy\Construct
 * @uses \Ergebnis\Classy\Exception\DirectoryDoesNotExist
 * @uses \Ergebnis\Classy\Exception\MultipleDefinitionsFound
 * @uses \Ergebnis\Classy\Exception\ParseError
 */
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
    public static function provideScenarioWithoutClassyConstructs(): iterable
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

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3
     */
    public function testFromSourceReturnsListOfClassyConstructsOnPhp73(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        $expected = \array_map(static function (Construct $construct): Construct {
            return Construct::fromName($construct->name());
        }, $scenario->constructsSortedByName());

        self::assertIsList($constructs);
        self::assertEquals($expected, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php74::classyConstructs
     *
     * @requires PHP >= 7.4
     */
    public function testFromSourceReturnsListOfClassyConstructsOnPhp74(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        $expected = \array_map(static function (Construct $construct): Construct {
            return Construct::fromName($construct->name());
        }, $scenario->constructsSortedByName());

        self::assertIsList($constructs);
        self::assertEquals($expected, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0
     */
    public function testFromSourceReturnsListOfClassyConstructsOnPhp80(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromSource($scenario->source());

        $expected = \array_map(static function (Construct $construct): Construct {
            return Construct::fromName($construct->name());
        }, $scenario->constructsSortedByName());

        self::assertIsList($constructs);
        self::assertEquals($expected, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php81::classyConstructs
     *
     * @requires PHP >= 8.1
     */
    public function testFromSourceReturnsListOfClassyConstructsOnPhp81(Test\Util\Scenario $scenario): void
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

    /**
     * @dataProvider provideScenarioWithoutClassyConstructs
     */
    public function testFromDirectoryReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertSame([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3
     */
    public function testFromDirectoryReturnsListOfClassyConstructsOnPhp73(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertIsList($constructs);
        self::assertEquals($scenario->constructsSortedByName(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php74::classyConstructs
     *
     * @requires PHP >= 7.4
     */
    public function testFromDirectoryReturnsListOfClassyConstructsOnPhp74(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertIsList($constructs);
        self::assertEquals($scenario->constructsSortedByName(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0
     */
    public function testFromDirectoryReturnsListOfClassyConstructsOnPhp80(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertIsList($constructs);
        self::assertEquals($scenario->constructsSortedByName(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php81::classyConstructs
     *
     * @requires PHP >= 8.1
     */
    public function testFromDirectoryReturnsListOfClassyConstructsOnPhp81(Test\Util\Scenario $scenario): void
    {
        $constructs = Constructs::fromDirectory($scenario->directory());

        self::assertIsList($constructs);
        self::assertEquals($scenario->constructsSortedByName(), $constructs);
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

    private static function assertIsList(array $actual): void
    {
        self::assertSame(\array_values($actual), $actual);
    }
}
