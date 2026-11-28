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
 * @covers \Ergebnis\Classy\Collector\TokenGetAllConstructFromSourceCollector
 *
 * @uses \Ergebnis\Classy\ConstructFromSource
 * @uses \Ergebnis\Classy\Exception\SourceCouldNotBeParsed
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Source
 * @uses \Ergebnis\Classy\Type
 */
final class TokenGetAllConstructFromSourceCollectorTest extends Framework\TestCase
{
    public function testCollectFromSourceThrowsSourceCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = Classy\Source::fromString(<<<'TXT'
            <?php

            final class MessedUp
            {
            TXT);

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $this->expectException(Classy\Exception\SourceCouldNotBeParsed::class);

        $collector->collectFromSource($source);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     */
    public function testCollectFromSourceReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php73::classyConstructs
     *
     * @requires PHP >= 7.3.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp73(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals($scenario->constructs(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php74::classyConstructs
     *
     * @requires PHP >= 7.4.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp74(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals($scenario->constructs(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp80(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals($scenario->constructs(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php81::classyConstructs
     *
     * @requires PHP >= 8.1.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp81(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\TokenGetAllConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals($scenario->constructs(), $constructs);
    }
}
