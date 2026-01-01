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

use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Source;

final class DefaultConstructFromSplFileInfoCollector implements ConstructFromSplFileInfoCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromSplFileInfo(\SplFileInfo $splFileInfo): array
    {
        $filePath = FilePath::fromString($splFileInfo->getPathname());

        if (!$splFileInfo->isFile()) {
            throw Exception\FileDoesNotExist::atFilePath($filePath);
        }

        $contents = \file_get_contents($filePath->toString());

        if (!\is_string($contents)) {
            throw Exception\FileCouldNotBeRead::atFilePath($filePath);
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

        return \array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
            return ConstructFromSplFileInfo::create(
                $splFileInfo,
                $constructFromSource->name(),
                $constructFromSource->type(),
            );
        }, $constructsFromSource);
    }
}
