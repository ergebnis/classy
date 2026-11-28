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

namespace Ergebnis\Classy\Test\Unit;

use Ergebnis\Classy;
use PHPUnit\Framework;

/**
 * @covers \Ergebnis\Classy\Type
 */
final class TypeTest extends Framework\TestCase
{
    use Classy\Test\Util\Helper;

    public function testClassReturnsType(): void
    {
        $type = Classy\Type::class();

        self::assertSame('class', $type->toString());
    }

    public function testEnumReturnsType(): void
    {
        $type = Classy\Type::enum();

        self::assertSame('enum', $type->toString());
    }

    public function testInterfaceReturnsType(): void
    {
        $type = Classy\Type::interface();

        self::assertSame('interface', $type->toString());
    }

    public function testTraitReturnsType(): void
    {
        $type = Classy\Type::trait();

        self::assertSame('trait', $type->toString());
    }

    public function testEqualsReturnsFalseWhenValuesAreDifferent(): void
    {
        $one = Classy\Type::class();
        $two = Classy\Type::interface();

        self::assertFalse($one->equals($two));
    }

    public function testEqualsReturnsTrueWhenValuesAreEqual(): void
    {
        $one = Classy\Type::class();
        $two = Classy\Type::class();

        self::assertTrue($one->equals($two));
    }
}
