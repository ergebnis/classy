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

use Ergebnis\Classy;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\ConstructFromSource
 *
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromSourceTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testCreateReturnsConstructFromSource(): void
    {
        $faker = self::faker();

        $name = Classy\Name::fromString($faker->word());

        /** @var Classy\Type $type */
        $type = $faker->randomElement([
            Classy\Type::class(),
            Classy\Type::enum(),
            Classy\Type::interface(),
            Classy\Type::trait(),
        ]);

        $construct = Classy\ConstructFromSource::create(
            $name,
            $type,
        );

        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
