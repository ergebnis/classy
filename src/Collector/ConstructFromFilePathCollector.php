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

use Ergebnis\Classy\ConstructFromFilePath;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\FilePath;

interface ConstructFromFilePathCollector
{
    /**
     * Returns a list of constructs defined in a file.
     *
     * @throws Exception\FileDoesNotExist
     * @throws Exception\FileCouldNotBeParsed
     * @throws Exception\FileCouldNotBeRead
     *
     * @return list<ConstructFromFilePath>
     */
    public function collectFromFilePath(FilePath $filePath): array;
}
