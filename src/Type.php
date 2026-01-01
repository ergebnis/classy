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

namespace Ergebnis\Classy;

final class Type
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function class(): self
    {
        return new self('class');
    }

    public static function enum(): self
    {
        return new self('enum');
    }

    public static function interface(): self
    {
        return new self('interface');
    }

    public static function trait(): self
    {
        return new self('trait');
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
