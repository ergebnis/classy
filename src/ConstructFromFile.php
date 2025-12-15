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

final class ConstructFromFile implements Construct
{
    private File $filename;
    private Name $name;
    private Type $type;

    private function __construct(
        File $filename,
        Name $name,
        Type $type
    ) {
        $this->filename = $filename;
        $this->name = $name;
        $this->type = $type;
    }

    public static function create(
        File $filename,
        Name $name,
        Type $type
    ): self {
        return new self(
            $filename,
            $name,
            $type,
        );
    }

    public function file(): File
    {
        return $this->filename;
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
