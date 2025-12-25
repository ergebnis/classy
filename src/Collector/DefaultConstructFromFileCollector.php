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
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Source;

final class DefaultConstructFromFileCollector implements ConstructFromFileCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromFile(FilePath $filePath): array
    {
        if (!\is_file($filePath->toString())) {
            throw Exception\FileDoesNotExist::at($filePath);
        }

        $contents = \file_get_contents($filePath->toString());

        if (!\is_string($contents)) {
            throw Exception\FileCouldNotBeRead::at($filePath);
        }

        if ('' === \trim($contents)) {
            throw Exception\FileCouldNotBeParsed::atFilePathWithBlankOrEmptyContent($filePath);
        }

        $source = Source::fromString($contents);

        try {
            $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
        } catch (Exception\SourceCouldNotBeParsed $exception) {
            /** @var \ParseError $parseError */
            $parseError = $exception->getPrevious();

            throw Exception\FileCouldNotBeParsed::atFilePathWithParseError(
                $filePath,
                $parseError,
            );
        }

        return \array_map(static function (ConstructFromSource $constructFromSource) use ($filePath): ConstructFromFile {
            return ConstructFromFile::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $constructsFromSource);
    }
}
