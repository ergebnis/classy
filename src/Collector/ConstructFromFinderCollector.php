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

interface ConstructFromFinderCollector
{
    /**
     * Returns a list of constructs defined in files found by the finder.
     *
     * @param iterable<\SplFileInfo> $finder
     *
     * @throws Classy\Exception\FileCouldNotBeParsed
     *
     * @return list<Classy\ConstructFromSplFileInfo>
     */
    public function collectFromFinder(iterable $finder): array;
}
