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

            $source = \file_get_contents($splFileInfo->getPathname());

            if (!\is_string($source)) {
                continue;
            }

            try {
                $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
            } catch (Exception\SourceCouldNotBeParsed $exception) {
                continue;
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
