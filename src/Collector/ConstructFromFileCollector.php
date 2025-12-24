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

use Ergebnis\Classy\ConstructFromFile;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\File;

interface ConstructFromFileCollector
{
    /**
     * Returns a list of constructs defined in a file.
     *
     * @throws Exception\FileCouldNotBeRead
     * @throws Exception\FileDoesNotExist
     * @throws Exception\FileCouldNotBeParsed
     *
     * @return list<ConstructFromFile>
     */
    public function collectFromFile(File $file): array;
}
