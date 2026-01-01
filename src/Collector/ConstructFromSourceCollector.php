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
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Source;

interface ConstructFromSourceCollector
{
    /**
     * Returns a list of constructs defined in source code.
     *
     * @throws Exception\SourceCouldNotBeParsed
     *
     * @return list<ConstructFromSource>
     */
    public function collectFromSource(Source $source): array;
}
