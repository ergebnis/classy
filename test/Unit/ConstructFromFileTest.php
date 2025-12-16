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

use Ergebnis\Classy\ConstructFromFile;
use Ergebnis\Classy\File;
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Test;
use Ergebnis\Classy\Type;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\ConstructFromFile
 *
 * @uses \Ergebnis\Classy\File
 * @uses \Ergebnis\Classy\Name
 * @uses \Ergebnis\Classy\Type
 */
final class ConstructFromFileTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsConstructFromFile(): void
    {
        $faker = self::faker();

        $file = File::fromString(__FILE__);
        $name = Name::fromString($faker->word());

        /** @var Type $type */
        $type = $faker->randomElement([
            Type::class(),
            Type::enum(),
            Type::interface(),
            Type::trait(),
        ]);

        $construct = ConstructFromFile::create(
            $file,
            $name,
            $type,
        );

        self::assertSame($file, $construct->file());
        self::assertSame($name, $construct->name());
        self::assertSame($type, $construct->type());
    }
}
