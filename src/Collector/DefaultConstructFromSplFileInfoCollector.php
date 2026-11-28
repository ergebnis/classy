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

final class DefaultConstructFromSplFileInfoCollector implements ConstructFromSplFileInfoCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromSplFileInfo(\SplFileInfo $splFileInfo): array
    {
        $filePath = Classy\FilePath::fromString($splFileInfo->getPathname());

        if (!$splFileInfo->isFile()) {
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

        return \array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($splFileInfo): Classy\ConstructFromSplFileInfo {
            return Classy\ConstructFromSplFileInfo::create(
                $splFileInfo,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $constructsFromSource);
    }
}
