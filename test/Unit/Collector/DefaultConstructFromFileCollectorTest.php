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

namespace Ergebnis\Classy\Test\Unit\Collector;

use Ergebnis\Classy\Collector;
use Ergebnis\Classy\ConstructFromFilePath;
use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Collector\DefaultConstructFromFileCollector
 *
 * @uses \Ergebnis\Classy\Collector\TokenGetAllConstructFromSourceCollector
 * @uses \Ergebnis\Classy\ConstructFromFilePath
 * @uses \Ergebnis\Classy\ConstructFromSource
 * @uses \Ergebnis\Classy\Exception\FileCouldNotBeParsed
 * @uses \Ergebnis\Classy\Exception\FileDoesNotExist
 * @uses \Ergebnis\Classy\Exception\SourceCouldNotBeParsed
 * @uses \Ergebnis\Classy\FilePath
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Source
 * @uses \Ergebnis\Classy\Type
 */
final class DefaultConstructFromFileCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    protected function setUp(): void
    {
        self::filesystem()->mkdir(self::temporaryDirectory());
    }

    protected function tearDown(): void
    {
        self::filesystem()->remove(self::temporaryDirectory());
    }

    public function testCollectFromFileThrowsFileDoesNotExistWhenFileDoesNotExist(): void
    {
        $filePath = FilePath::fromString(\sprintf(
            '%s/does-not-exist',
            self::temporaryDirectory(),
        ));

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileDoesNotExist::class);

        $collector->collectFromFile($filePath);
    }

    public function testCollectFromFileThrowsFileCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = <<<'TXT'
<?php

final class MessedUp
{
TXT;

        $filePath = self::filePathWithSource($source);

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileCouldNotBeParsed::class);

        $collector->collectFromFile($filePath);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     */
    public function testCollectFromFileReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFile($filePath);

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3
     */
    public function testCollectFromFileReturnsArrayWithConstructsFromFilePathOnPhp73(Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFile($filePath);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($filePath): ConstructFromFilePath {
            return ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php74::classyConstructs
     *
     * @requires PHP >= 7.4
     */
    public function testCollectFromFileReturnsArrayWithConstructsFromFilePathOnPhp74(Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFile($filePath);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($filePath): ConstructFromFilePath {
            return ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0
     */
    public function testCollectFromFileReturnsArrayWithConstructsFromFilePathOnPhp80(Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFile($filePath);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($filePath): ConstructFromFilePath {
            return ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php81::classyConstructs
     *
     * @requires PHP >= 8.1
     */
    public function testCollectFromFileReturnsArrayWithConstructsFromFilePathOnPhp81(Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromFileCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFile($filePath);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($filePath): ConstructFromFilePath {
            return ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    private static function filePathWithSource(string $source): FilePath
    {
        $filePath = FilePath::fromString(\sprintf(
            '%s/source.php',
            self::temporaryDirectory(),
        ));

        self::filesystem()->dumpFile(
            $filePath->toString(),
            $source,
        );

        return $filePath;
    }
}
