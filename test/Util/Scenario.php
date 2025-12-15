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

namespace Ergebnis\Classy\Test\Util;

use Ergebnis\Classy;

final class Scenario
{
    private string $description;
    private string $fileName;
    private string $source;

    /**
     * @var list<Classy\ConstructFromSource>
     */
    private array $constructs;

    private function __construct(
        string $description,
        string $fileName,
        string $source,
        Classy\ConstructFromSource ...$constructs
    ) {
        $this->description = $description;
        $this->fileName = $fileName;
        $this->source = $source;
        $this->constructs = $constructs;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(
        string $description,
        string $fileName,
        Classy\ConstructFromSource ...$constructs
    ): self {
        if (!\is_file($fileName)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" does not exist.',
                $fileName,
            ));
        }

        $source = \file_get_contents($fileName);

        if (!\is_string($source)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" could not be read.',
                $fileName,
            ));
        }

        $resolvedFileName = \realpath($fileName);

        if (!\is_string($resolvedFileName)) {
            throw new \RuntimeException(\sprintf(
                'Failed resolving the real path of "%s".',
                $fileName,
            ));
        }

        return new self(
            $description,
            $fileName,
            $source,
            ...$constructs,
        );
    }

    public function description(): string
    {
        return $this->description;
    }

    public function directory(): string
    {
        return \dirname($this->fileName);
    }

    public function source(): string
    {
        return $this->source;
    }

    /**
     * @return list<Classy\ConstructFromSource>
     */
    public function constructs(): array
    {
        return $this->constructs;
    }
}
