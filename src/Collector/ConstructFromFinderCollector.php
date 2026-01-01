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

use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Exception;

interface ConstructFromFinderCollector
{
    /**
     * Returns a list of constructs defined in files found by the finder.
     *
     * @param iterable<\SplFileInfo> $finder
     *
     * @throws Exception\FileCouldNotBeParsed
     *
     * @return list<ConstructFromSplFileInfo>
     */
    public function collectFromFinder(iterable $finder): array;
}
