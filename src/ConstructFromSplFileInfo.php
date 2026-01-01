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

final class ConstructFromSplFileInfo implements Construct
{
    private \SplFileInfo $splFileInfo;
    private Name $name;
    private Type $type;

    private function __construct(
        \SplFileInfo $splFileInfo,
        Name $name,
        Type $type
    ) {
        $this->splFileInfo = $splFileInfo;
        $this->name = $name;
        $this->type = $type;
    }

    public static function create(
        \SplFileInfo $splFileInfo,
        Name $name,
        Type $type
    ): self {
        return new self(
            $splFileInfo,
            $name,
            $type,
        );
    }

    public function splFileInfo(): \SplFileInfo
    {
        return $this->splFileInfo;
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
