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

use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Exception;

final class DefaultConstructFromSplFileInfoCollector implements ConstructFromSplFileInfoCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromSplFileInfo(\SplFileInfo $splFileInfo): array
    {
        if (!$splFileInfo->isFile()) {
            throw Exception\FileDoesNotExist::at($splFileInfo->getPathname());
        }

        $source = \file_get_contents($splFileInfo->getPathname());

        if (!\is_string($source)) {
            throw Exception\FileCouldNotBeRead::at($splFileInfo->getPathname());
        }

        try {
            $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
        } catch (Exception\SourceCouldNotBeParsed $exception) {
            /** @var \ParseError $parseError */
            $parseError = $exception->getPrevious();

            throw Exception\FileCouldNotBeParsed::fromFileAndParseError(
                $splFileInfo->getPathname(),
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
