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

namespace Ergebnis\Classy\Collector;

use Ergebnis\Classy;

final class DefaultConstructFromFilePathCollector implements ConstructFromFilePathCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromFilePath(Classy\FilePath $filePath): array
    {
        if (!\is_file($filePath->toString())) {
            throw Classy\Exception\FileDoesNotExist::atFilePath($filePath);
        }

        $contents = \file_get_contents($filePath->toString());

        if (!\is_string($contents)) {
            throw Classy\Exception\FileCouldNotBeRead::atFilePath($filePath);
        }

        $source = Classy\Source::fromString($contents);

        try {
            $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
        } catch (Classy\Exception\SourceCouldNotBeParsed $sourceCouldNotBeParsed) {
            /** @var \ParseError $parseError */
            $parseError = $sourceCouldNotBeParsed->getPrevious();

            throw Classy\Exception\FileCouldNotBeParsed::atFilePathWithParseError(
                $filePath,
                $parseError,
            );
        }

        return \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($filePath): Classy\ConstructFromFilePath {
            return Classy\ConstructFromFilePath::create(
                $filePath,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $constructsFromSource);
    }
}
