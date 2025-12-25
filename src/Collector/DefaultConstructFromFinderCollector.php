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
use Ergebnis\Classy\FilePath;
use Ergebnis\Classy\Source;

final class DefaultConstructFromFinderCollector implements ConstructFromFinderCollector
{
    private ConstructFromSourceCollector $constructFromSourceCollector;

    public function __construct(ConstructFromSourceCollector $constructFromSourceCollector)
    {
        $this->constructFromSourceCollector = $constructFromSourceCollector;
    }

    public function collectFromFinder(iterable $finder): array
    {
        $constructs = [];

        foreach ($finder as $splFileInfo) {
            if (!$splFileInfo->isFile()) {
                continue;
            }

            $filePath = FilePath::fromString($splFileInfo->getPathname());

            $contents = \file_get_contents($filePath->toString());

            if (!\is_string($contents)) {
                continue;
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

            \array_push(
                $constructs,
                ...\array_map(static function (ConstructFromSource $constructFromSource) use ($splFileInfo): ConstructFromSplFileInfo {
                    return ConstructFromSplFileInfo::create(
                        $splFileInfo,
                        $constructFromSource->name(),
                        $constructFromSource->type(),
                    );
                }, $constructsFromSource),
            );
        }

        return $constructs;
    }
}
