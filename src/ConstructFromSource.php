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

namespace Ergebnis\Classy;

final class ConstructFromSource implements Construct
{
    private Name $name;
    private Type $type;

    private function __construct(
        Name $name,
        Type $type
    ) {
        $this->name = $name;
        $this->type = $type;
    }

    public static function create(
        Name $name,
        Type $type
    ): self {
        return new self(
            $name,
            $type,
        );
    }

    public function name(): Name
    {
        return $this->name;
    }

    public function type(): Type
    {
        return $this->type;
    }
}
