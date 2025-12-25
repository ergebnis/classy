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
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Test;
use Ergebnis\Classy\Type;
use PHPUnit\Framework;
use Symfony\Component\Finder;

/**
 * @covers \Ergebnis\Classy\Collector\DefaultConstructFromFinderCollector
 *
 * @uses \Ergebnis\Classy\Collector\DefaultConstructFromSplFileInfoCollector
 * @uses \Ergebnis\Classy\Collector\TokenGetAllConstructFromSourceCollector
 * @uses \Ergebnis\Classy\ConstructFromFilePath
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
final class DefaultConstructFromFinderCollectorTest extends Framework\TestCase
{
    use Test\Util\Helper;

    protected function setUp(): void
    {
        self::filesystem()->mkdir(self::temporaryDirectory());

        $source = <<<'TXT'
<?php

final class MessedUp
{
TXT;

        self::filesystem()->dumpFile(
            self::fileWithParseError(),
            $source,
        );
    }

    protected function tearDown(): void
    {
        self::filesystem()->remove([
            self::temporaryDirectory(),
            self::fileWithParseError(),
        ]);
    }

    public function testCollectFromFinderReturnsEmptyArrayWhenFinderIsEmpty(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in(self::temporaryDirectory());

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertEquals([], $constructs);
    }

    public function testCollectFromFinderReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in(__DIR__ . '/../../Fixture/NoClassy');

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertEquals([], $constructs);
    }

    public function testCollectFromFinderThrowsFileCouldNotBeParsedWhenFinderYieldsSplFileInfoWithFileThatCanNotBeParsed(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/ParseError',
            ]);

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Exception\FileCouldNotBeParsed::class);

        $collector->collectFromFinder($finder);
    }

    /**
     * @requires PHP >= 7.3
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp73(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php73/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php73\WithinNamespace\Foo::class),
                Type::class(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php73\WithinNamespace\Bar::class),
                Type::interface(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php73\WithinNamespace\Baz::class),
                Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 7.4
     */
    public function testCollectFromFileReturnsArrayWithConstructsFromSplFileInfoOnPhp74(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php74/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Foo::class),
                Type::class(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Bar::class),
                Type::interface(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php74\WithinNamespace\Baz::class),
                Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp80(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php80/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php80\WithinNamespace\Foo::class),
                Type::class(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php80\WithinNamespace\Bar::class),
                Type::interface(),
            ),
            ConstructFromSource::create(
                Name::fromString(Test\Fixture\Classy\Php80\WithinNamespace\Baz::class),
                Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 8.1
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp81(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php81/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Collector\DefaultConstructFromFinderCollector(new Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(ConstructFromSplFileInfo::class, $constructs);

        $expected = [
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
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @return list<ConstructFromSource>
     */
    private static function normalize(ConstructFromSplFileInfo ...$constructFromSplFileInfo): array
    {
        return \array_map(static function (ConstructFromSplFileInfo $constructFromSplFileInfo): ConstructFromSource {
            return ConstructFromSource::create(
                $constructFromSplFileInfo->name(),
                $constructFromSplFileInfo->type(),
            );
        }, $constructFromSplFileInfo);
    }
}
