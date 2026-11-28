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
 * @covers \Ergebnis\Classy\ConstructFromSplFileInfo
 *
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromSplFileInfoTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testCreateReturnsConstructFromSplFileInfo(): void
    {
        $faker = self::faker();

        $splFileInfo = new \SplFileInfo(__FILE__);
        $name = Classy\Name::fromString($faker->word());

        /** @var Classy\Type $type */
        $type = $faker->randomElement([
            Classy\Type::class(),
            Classy\Type::enum(),
            Classy\Type::interface(),
            Classy\Type::trait(),
        ]);

        $construct = Classy\ConstructFromSplFileInfo::create(
            $splFileInfo,
            $name,
            $type,
        );

        self::assertSame($splFileInfo, $construct->splFileInfo());
        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
