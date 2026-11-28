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

            $filePath = Classy\FilePath::fromString($splFileInfo->getPathname());

            $contents = \file_get_contents($filePath->toString());

            if (!\is_string($contents)) {
                continue;
            }

            $source = Classy\Source::fromString($contents);

            try {
                $constructsFromSource = $this->constructFromSourceCollector->collectFromSource($source);
            } catch (Classy\Exception\SourceCouldNotBeParsed $exception) {
                /** @var \ParseError $parseError */
                $parseError = $exception->getPrevious();

                throw Classy\Exception\FileCouldNotBeParsed::atFilePathWithParseError(
                    $filePath,
                    $parseError,
                );
            }

            \array_push(
                $constructs,
                ...\array_map(static function (Classy\ConstructFromSource $constructFromSource) use ($splFileInfo): Classy\ConstructFromSplFileInfo {
                    return Classy\ConstructFromSplFileInfo::create(
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
