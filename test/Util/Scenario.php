<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2024 Andreas Möller
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
    private readonly PhpVersion $phpVersion;
    private readonly string $description;
    private readonly string $fileName;
    private readonly string $source;

    /**
     * @var list<Classy\Construct>
     */
    private readonly array $constructs;

    private function __construct(
        PhpVersion $phpVersion,
        string $description,
        string $fileName,
        string $source,
        Classy\Construct ...$constructs,
    ) {
        $this->phpVersion = $phpVersion;
        $this->description = $description;
        $this->fileName = $fileName;
        $this->source = $source;
        $this->constructs = \array_values($constructs);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(
        PhpVersion $phpVersion,
        string $description,
        string $fileName,
        Classy\Construct ...$constructs,
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

        \usort($constructs, static function (Classy\Construct $a, Classy\Construct $b): int {
            return \strcmp(
                $a->name(),
                $b->name(),
            );
        });

        return new self(
            $phpVersion,
            $description,
            $fileName,
            $source,
            ...\array_map(static function (Classy\Construct $construct) use ($resolvedFileName): Classy\Construct {
                return $construct->definedIn($resolvedFileName);
            }, \array_values($constructs)),
        );
    }

    public function phpVersion(): PhpVersion
    {
        return $this->phpVersion;
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
     * @return list<Classy\Construct>
     */
    public function constructsSortedByName(): array
    {
        return $this->constructs;
    }
}
