<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Util;

use Ergebnis\Classy;

/**
 * @psalm-immutable
 */
final class Scenario
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileContent;

    /**
     * @var array<int, Classy\Construct>
     */
    private $constructs;

    private function __construct(
        string $description,
        string $fileName,
        string $fileContent,
        Classy\Construct ...$constructs
    ) {
        $this->description = $description;
        $this->fileName = $fileName;
        $this->fileContent = $fileContent;
        $this->constructs = \array_values($constructs);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public static function create(
        string $description,
        string $fileName,
        Classy\Construct ...$constructs
    ): self {
        if (!\is_file($fileName)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" does not exist.',
                $fileName
            ));
        }

        $fileContent = \file_get_contents($fileName);

        if (!\is_string($fileContent)) {
            throw new \InvalidArgumentException(\sprintf(
                'File "%s" could not be read.',
                $fileName
            ));
        }

        $resolvedFileName = \realpath($fileName);

        if (!\is_string($resolvedFileName)) {
            throw new \RuntimeException(\sprintf(
                'Failed resolving the real path of "%s".',
                $fileName
            ));
        }

        \usort($constructs, static function (Classy\Construct $a, Classy\Construct $b): int {
            return \strcmp(
                $a->name(),
                $b->name()
            );
        });

        return new self(
            $description,
            $fileName,
            $fileContent,
            ...\array_map(static function (Classy\Construct $construct) use ($resolvedFileName): Classy\Construct {
                return $construct->definedIn($resolvedFileName);
            }, \array_values($constructs))
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
        return $this->fileContent;
    }

    /**
     * @return array<int, Classy\Construct>
     */
    public function constructsSortedByName(): array
    {
        return $this->constructs;
    }
}
