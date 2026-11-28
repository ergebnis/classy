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
 * @covers \Ergebnis\Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector
 *
 * @uses \Ergebnis\Classy\ConstructFromSource
 * @uses \Ergebnis\Classy\Exception\ParsingNotSupported
 * @uses \Ergebnis\Classy\Exception\SourceCouldNotBeParsed
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class PhpTokenTokenizeConstructFromSourceCollectorTest extends Framework\TestCase
{
    /**
     * @requires PHP <= 8.0.0
     */
    public function testConstructorThrowsParsingNotSupportedWhenPhpVersionIsLessThan8(): void
    {
        $this->expectException(Classy\Exception\ParsingNotSupported::class);

        new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();
    }

    /**
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromSourceThrowsSourceCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = Classy\Source::fromString(<<<'TXT'
            <?php

            final class MessedUp
            {
            TXT);

        $collector = new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $this->expectException(Classy\Exception\SourceCouldNotBeParsed::class);

        $collector->collectFromSource($source);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     *
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromSourceReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp80(Classy\Test\Util\Scenario $scenario): void
    {
        $source = Classy\Source::fromString($scenario->source());

        $collector = new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();

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

        $collector = new Classy\Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($source);

        self::assertEquals($scenario->constructs(), $constructs);
    }
}
