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

final class ConstructFromFilePath implements Construct
{
    private FilePath $filePath;
    private Name $name;
    private Type $type;

    private function __construct(
        FilePath $filePath,
        Name $name,
        Type $type
    ) {
        $this->filePath = $filePath;
        $this->name = $name;
        $this->type = $type;
    }

    public static function create(
        FilePath $filePath,
        Name $name,
        Type $type
    ): self {
        return new self(
            $filePath,
            $name,
            $type,
        );
    }

    public function filePath(): FilePath
    {
        return $this->filePath;
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
