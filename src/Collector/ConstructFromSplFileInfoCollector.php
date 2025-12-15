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

use Ergebnis\Classy\ConstructFromSplFileInfo;
use Ergebnis\Classy\Exception;

interface ConstructFromSplFileInfoCollector
{
    /**
     * Returns a list of constructs defined in the file located at the path a file.
     *
     * @throws Exception\FileDoesNotExist
     * @throws Exception\FileCouldNotBeRead
     * @throws Exception\FileCouldNotBeParsed
     *
     * @return list<ConstructFromSplFileInfo>
     */
    public function collectFromSplFileInfo(\SplFileInfo $splFileInfo): array;
}
