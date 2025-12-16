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

use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Test;
use Ergebnis\Classy\Type;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\ConstructFromSplFileInfo
 *
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromSplFileInfoTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsConstructFromSplFileInfo(): void
    {
        $faker = self::faker();

        $splFileInfo = new \SplFileInfo(__FILE__);
        $name = Name::fromString($faker->word());

        /** @var Type $type */
        $type = $faker->randomElement([
            Type::class(),
            Type::enum(),
            Type::interface(),
            Type::trait(),
        ]);

        $construct = ConstructFromSplFileInfo::create(
            $splFileInfo,
            $name,
            $type,
        );

        self::assertSame($splFileInfo, $construct->splFileInfo());
        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
