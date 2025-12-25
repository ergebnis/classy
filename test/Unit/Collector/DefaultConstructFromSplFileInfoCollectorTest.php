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
use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Collector\DefaultConstructFromSplFileInfoCollector
 *
 * @uses \Ergebnis\Classy\Collector\DefaultConstructFromFileCollector
 * @uses \Ergebnis\Classy\Collector\TokenGetAllConstructFromSourceCollector
 * @uses \Ergebnis\Classy\ConstructFromFile
 * @uses \Ergebnis\Classy\ConstructFromSource
 * @uses \Ergebnis\Classy\ConstructFromSplFileInfo
 * @uses \Ergebnis\Classy\Exception\FileCouldNotBeParsed
 * @uses \Ergebnis\Classy\Exception\FileDoesNotExist
 * @uses \Ergebnis\Classy\Exception\SourceCouldNotBeParsed
 * @uses \Ergebnis\Classy\FilePath
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Source
 * @uses \Ergebnis\Classy\Type
 */
final class DefaultConstructFromSplFileInfoCollectorTest extends Framework\TestCase
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

    public function testCollectFromSplFileInfoThrowsFileDoesNotExistWhenFileDoesNotExist(): void
    {
        $splFileInfo = new \SplFileInfo(\sprintf(
            '%s/does-not-exist',
            self::temporaryDirectory(),
        ));

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileDoesNotExist::class);

        $collector->collectFromSplFileInfo($splFileInfo);
    }

    public function testCollectFromSplFileInfoThrowsFileDoesNotExistWhenFileIsDirectory(): void
    {
        $file = \sprintf(
            '%s/does-not-exist',
            self::temporaryDirectory(),
        );

        self::filesystem()->mkdir($file);

        $splFileInfo = new \SplFileInfo($file);

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileDoesNotExist::class);

        $collector->collectFromSplFileInfo($splFileInfo);
    }

    public function testCollectFromSplFileInfoThrowsFileCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = <<<'TXT'
<?php

final class MessedUp
{
TXT;

        $splFileInfo = self::splFileInfoWithSource($source);

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileCouldNotBeParsed::class);

        $collector->collectFromSplFileInfo($splFileInfo);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     */
    public function testCollectFromSplFileInfoReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $splFileInfo = self::splFileInfoWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromSplFileInfo($splFileInfo);

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3
     */
    public function testCollectFromSplFileInfoReturnsArrayWithConstructsFromSplFileInfoOnPhp73(Test\Util\Scenario $scenario): void
    {
        $splFileInfo = self::splFileInfoWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromSplFileInfo($splFileInfo);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
            return ConstructFromSplFileInfo::create(
                $splFileInfo,
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
    public function testCollectFromSplFileInfoReturnsArrayWithConstructsFromSplFileInfoOnPhp74(Test\Util\Scenario $scenario): void
    {
        $splFileInfo = self::splFileInfoWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromSplFileInfo($splFileInfo);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
            return ConstructFromSplFileInfo::create(
                $splFileInfo,
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
    public function testCollectFromSplFileInfoReturnsArrayWithConstructsFromSplFileInfoOnPhp80(Test\Util\Scenario $scenario): void
    {
        $splFileInfo = self::splFileInfoWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromSplFileInfo($splFileInfo);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
            return ConstructFromSplFileInfo::create(
                $splFileInfo,
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
    public function testCollectFromSplFileInfoReturnsArrayWithConstructsFromSplFileInfoOnPhp81(Test\Util\Scenario $scenario): void
    {
        $splFileInfo = self::splFileInfoWithSource($scenario->source());

        $collector = new Collector\DefaultConstructFromSplFileInfoCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromSplFileInfo($splFileInfo);

        $expectedConstructs = \array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
            return ConstructFromSplFileInfo::create(
                $splFileInfo,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $scenario->constructs());

        self::assertEquals($expectedConstructs, $constructs);
    }

    private static function splFileInfoWithSource(string $source): \SplFileInfo
    {
        $file = \sprintf(
            '%s/source.php',
            self::temporaryDirectory(),
        );

        self::filesystem()->dumpFile(
            $file,
            $source,
        );

        return new \SplFileInfo($file);
    }
}
