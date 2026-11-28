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
 * @covers \Ergebnis\Classy\ConstructFromFilePath
 *
 * @uses \Ergebnis\Classy\FilePath
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromFilePathTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testCreateReturnsConstructFromFilePath(): void
    {
        $faker = self::faker();

        $filePath = Classy\FilePath::fromString(__FILE__);
        $name = Classy\Name::fromString($faker->word());

        /** @var Classy\Type $type */
        $type = $faker->randomElement([
            Classy\Type::class(),
            Classy\Type::enum(),
            Classy\Type::interface(),
            Classy\Type::trait(),
        ]);

        $construct = Classy\ConstructFromFilePath::create(
            $filePath,
            $name,
            $type,
        );

        self::assertSame($filePath, $construct->filePath());
        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
