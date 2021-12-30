<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Classy\Construct
 */
final class ConstructTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testFromNameReturnsConstruct(): void
    {
        $name = self::faker()->word();

        $construct = Construct::fromName($name);

        self::assertSame($name, $construct->name());
    }

    public function testDefaults(): void
    {
        $construct = Construct::fromName(self::faker()->word());

        self::assertCount(0, $construct->fileNames());
    }

    public function testToStringReturnsName(): void
    {
        $name = self::faker()->word();

        $construct = Construct::fromName($name);

        self::assertSame($name, $construct->__toString());
    }

    public function testDefinedInClonesInstanceAndAddsFileNames(): void
    {
        $faker = self::faker();

        $name = $faker->word();

        $fileNames = \array_map(static function () use ($faker): string {
            return \sprintf(
                '%s.%s',
                $faker->word(),
                $faker->fileExtension(),
            );
        }, \range(0, 5));

        $construct = Construct::fromName($name);

        $mutated = $construct->definedIn(...$fileNames);

        self::assertNotSame($construct, $mutated);
        self::assertSame($name, $mutated->name());
        self::assertCount(\count($fileNames), $mutated->fileNames());
        self::assertEquals($fileNames, $mutated->fileNames());
    }
}
