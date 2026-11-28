<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit\Collector;

use Ergebnis\Classy;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Collector\DefaultConstructFromFilePathCollector
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
final class DefaultConstructFromFilePathCollectorTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    protected function setUp(): void
    {
        self::filesystem()->mkdir(self::temporaryDirectory());
    }

    protected function tearDown(): void
    {
        self::filesystem()->remove(self::temporaryDirectory());
    }

    public function testCollectFromFilePathThrowsFileDoesNotExistWhenFileDoesNotExist(): void
    {
        $filePath = Classy\FilePath::fromString(\sprintf(
            '%s/does-not-exist',
            self::temporaryDirectory(),
        ));

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Classy\Exception\FileDoesNotExist::class);

        $collector->collectFromFilePath($filePath);
    }

    public function testCollectFromFilePathThrowsFileCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = <<<'TXT'
            <?php

            final class MessedUp
            {
            TXT;

        $filePath = self::filePathWithSource($source);

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Classy\Exception\FileCouldNotBeParsed::class);

        $collector->collectFromFilePath($filePath);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     */
    public function testCollectFromFilePathReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Classy\Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFilePath($filePath);

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3.0
     */
    public function testCollectFromFilePathReturnsArrayWithConstructsFromFilePathOnPhp73(Classy\Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFilePath($filePath);

        $expectedConstructs = \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($filePath): Classy\ConstructFromFilePath {
            return Classy\ConstructFromFilePath::create(
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
     * @requires PHP >= 7.4.0
     */
    public function testCollectFromFilePathReturnsArrayWithConstructsFromFilePathOnPhp74(Classy\Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFilePath($filePath);

        $expectedConstructs = \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($filePath): Classy\ConstructFromFilePath {
            return Classy\ConstructFromFilePath::create(
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
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromFilePathReturnsArrayWithConstructsFromFilePathOnPhp80(Classy\Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFilePath($filePath);

        $expectedConstructs = \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($filePath): Classy\ConstructFromFilePath {
            return Classy\ConstructFromFilePath::create(
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
     * @requires PHP >= 8.1.0
     */
    public function testCollectFromFilePathReturnsArrayWithConstructsFromFilePathOnPhp81(Classy\Test\Util\Scenario $scenario): void
    {
        $filePath = self::filePathWithSource($scenario->source());

        $collector = new Classy\Collector\DefaultConstructFromFilePathCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFilePath($filePath);

        $expectedConstructs = \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($filePath): Classy\ConstructFromFilePath {
            return Classy\ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    private static function filePathWithSource(string $source): Classy\FilePath
    {
        $filePath = Classy\FilePath::fromString(\sprintf(
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
