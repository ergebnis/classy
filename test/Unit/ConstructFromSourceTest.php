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

use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Test;
use Ergebnis\Classy\Type;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\ConstructFromSource
 *
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromSourceTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsConstructFromSource(): void
    {
        $faker = self::faker();

        $name = Name::fromString($faker->word());

        /** @var Type $type */
        $type = $faker->randomElement([
            Type::class(),
            Type::enum(),
            Type::interface(),
            Type::trait(),
        ]);

        $construct = ConstructFromSource::create(
            $name,
            $type,
        );

        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
