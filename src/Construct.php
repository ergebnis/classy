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

final class Construct
{
    private string $name;

    /**
     * @var list<string>
     */
    private array $fileNames = [];

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @deprecated Will be removed in the next major release.
     *
     * Returns a string representation of the construct.
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * Creates a new construct from a construct name.
     */
    public static function fromName(string $name): self
    {
        return new self($name);
    }

    /**
     * Returns the name of the construct.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Returns a list of file names in which the construct is defined.
     *
     * @return list<string>
     */
    public function fileNames(): array
    {
        return $this->fileNames;
    }

    /**
     * Clones the construct and adds the file name to the list of files the construct is defined in.
     */
    public function definedIn(string ...$fileNames): self
    {
        $instance = clone $this;

        foreach ($fileNames as $fileName) {
            $instance->fileNames[] = $fileName;
        }

        return $instance;
    }
}
