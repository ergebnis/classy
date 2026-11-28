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

interface ConstructFromFilePathCollector
{
    /**
     * Returns a list of constructs defined in a file.
     *
     * @throws Classy\Exception\FileDoesNotExist
     * @throws Classy\Exception\FileCouldNotBeParsed
     * @throws Classy\Exception\FileCouldNotBeRead
     *
     * @return list<Classy\ConstructFromFilePath>
     */
    public function collectFromFilePath(Classy\FilePath $filePath): array;
}
