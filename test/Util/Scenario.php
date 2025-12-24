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
    private string $filePath;
    private string $source;

    /**
     * @var list<Classy\ConstructFromSource>
     */
    private array $constructs;

    private function __construct(
        string $description,
        string $filePath,
        string $source,
        Classy\ConstructFromSource ...$constructs
    ) {
        $this->description = $description;
        $this->filePath = $filePath;
        $this->source = $source;
        $this->constructs = $constructs;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(
        string $description,
        string $filePath,
        Classy\ConstructFromSource ...$constructs
    ): self {
        if (!\is_file($filePath)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" does not exist.',
                $filePath,
            ));
        }

        $source = \file_get_contents($filePath);

        if (!\is_string($source)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" could not be read.',
                $filePath,
            ));
        }

        $resolvedFileName = \realpath($filePath);

        if (!\is_string($resolvedFileName)) {
            throw new \RuntimeException(\sprintf(
                'Failed resolving the real path of "%s".',
                $filePath,
            ));
        }

        return new self(
            $description,
            $filePath,
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
        return \dirname($this->filePath);
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
