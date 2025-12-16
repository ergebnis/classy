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
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
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
     * @requires PHP <= 8.0
     */
    public function testConstructorThrowsParsingNotSupportedWhenPhpVersionIsLessThan8(): void
    {
        $this->expectException(Exception\ParsingNotSupported::class);

        new Collector\PhpTokenTokenizeConstructFromSourceCollector();
    }

    /**
     * @requires PHP >= 8.0
     */
    public function testCollectFromSourceThrowsSourceCouldNotBeParsedWhenParseErrorIsThrownDuringParsing(): void
    {
        $source = <<<'TXT'
<?php

final class MessedUp
{
TXT;

        $collector = new Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $this->expectException(Exception\SourceCouldNotBeParsed::class);

        $collector->collectFromSource($source);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Any::noClassyConstructs
     *
     * @requires PHP >= 8.0
     */
    public function testCollectFromSourceReturnsEmptyArrayWhenNoClassyConstructsHaveBeenFound(Test\Util\Scenario $scenario): void
    {
        $collector = new Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($scenario->source());

        self::assertEquals([], $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php80::classyConstructs
     *
     * @requires PHP >= 8.0
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp80(Test\Util\Scenario $scenario): void
    {
        $collector = new Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($scenario->source());

        self::assertEquals($scenario->constructs(), $constructs);
    }

    /**
     * @dataProvider \Ergebnis\Classy\Test\DataProvider\Php81::classyConstructs
     *
     * @requires PHP >= 8.1
     */
    public function testCollectFromSourceReturnsArrayWithConstructsFromSourceOnPhp81(Test\Util\Scenario $scenario): void
    {
        $collector = new Collector\PhpTokenTokenizeConstructFromSourceCollector();

        $constructs = $collector->collectFromSource($scenario->source());

        self::assertEquals($scenario->constructs(), $constructs);
    }
}
