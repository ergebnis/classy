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

namespace Ergebnis\Classy\Collector;

use Ergebnis\Classy\ConstructFromFile;
use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\File;

final class DefaultConstructFromFileCollector implements ConstructFromFileCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromFile(string $file): array
    {
        if (!\is_file($file)) {
            throw Exception\FileDoesNotExist::at($file);
        }

        $source = \file_get_contents($file);

        if (!\is_string($source)) {
            throw Exception\FileCouldNotBeRead::at($file);
        }

        $filename = File::fromString($file);

        try {
            $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
        } catch (Exception\SourceCouldNotBeParsed $exception) {
            /** @var \ParseError $parseError */
            $parseError = $exception->getPrevious();

            throw Exception\FileCouldNotBeParsed::fromFileAndParseError(
                $file,
                $parseError,
            );
        }

        return \array_map(static function (ConstructFromSource $constructFromSource) use ($filename): ConstructFromFile {
            return ConstructFromFile::create(
                $filename,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $constructsFromSource);
    }
}
