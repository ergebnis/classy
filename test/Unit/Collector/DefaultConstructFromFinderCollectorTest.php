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
    use Classy\Test\Util\Helper;

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

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertEquals([], $constructs);
    }

    public function testCollectFromFinderReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in(__DIR__ . '/../../Fixture/NoClassy');

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

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

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $this->expectException(Classy\Exception\FileCouldNotBeParsed::class);

        $collector->collectFromFinder($finder);
    }

    /**
     * @requires PHP >= 7.3.0
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp73(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php73/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(Classy\ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php73\WithinNamespace\Foo::class),
                Classy\Type::class(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php73\WithinNamespace\Bar::class),
                Classy\Type::interface(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php73\WithinNamespace\Baz::class),
                Classy\Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 7.4.0
     */
    public function testCollectFromFilePathReturnsArrayWithConstructsFromSplFileInfoOnPhp74(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php74/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(Classy\ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php74\WithinNamespace\Foo::class),
                Classy\Type::class(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php74\WithinNamespace\Bar::class),
                Classy\Type::interface(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php74\WithinNamespace\Baz::class),
                Classy\Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp80(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php80/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(Classy\ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php80\WithinNamespace\Foo::class),
                Classy\Type::class(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php80\WithinNamespace\Bar::class),
                Classy\Type::interface(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php80\WithinNamespace\Baz::class),
                Classy\Type::trait(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @requires PHP >= 8.1.0
     */
    public function testCollectFromFinderReturnsArrayWithConstructsFromSplFileInfoOnPhp81(): void
    {
        $finder = Finder\Finder::create()
            ->files()
            ->in([
                __DIR__ . '/../../Fixture/Classy/Php81/WithinNamespace',
                __DIR__ . '/../../Fixture/NoClassy',
            ]);

        $collector = new Classy\Collector\DefaultConstructFromFinderCollector(new Classy\Collector\TokenGetAllConstructFromSourceCollector());

        $constructs = $collector->collectFromFinder($finder);

        self::assertContainsOnlyInstancesOf(Classy\ConstructFromSplFileInfo::class, $constructs);

        $expected = [
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php81\WithinNamespace\Foo::class),
                Classy\Type::class(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php81\WithinNamespace\Bar::class),
                Classy\Type::interface(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php81\WithinNamespace\Baz::class),
                Classy\Type::trait(),
            ),
            Classy\ConstructFromSource::create(
                Classy\Name::fromString(Classy\Test\Fixture\Classy\Php81\WithinNamespace\Qux::class),
                Classy\Type::enum(),
            ),
        ];

        self::assertEquals($expected, self::normalize(...$constructs));
    }

    /**
     * @return list<Classy\ConstructFromSource>
     */
    private static function normalize(Classy\ConstructFromSplFileInfo ...$constructFromSplFileInfo): array
    {
        return \array_map(static function (Classy\ConstructFromSplFileInfo $constructFromSplFileInfo): Classy\ConstructFromSource {
            return Classy\ConstructFromSource::create(
                $constructFromSplFileInfo->name(),
                $constructFromSplFileInfo->type(),
            );
        }, $constructFromSplFileInfo);
    }
}
