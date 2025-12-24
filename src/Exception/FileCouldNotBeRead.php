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

namespace Ergebnis\Classy\Exception;

use Ergebnis\Classy\File;

final class FileCouldNotBeRead extends \RuntimeException implements ExceptionInterface
{
    public static function at(File $file): self
    {
        return new self(\sprintf(
            'File "%s" could not be read.',
            $file->toString(),
        ));
    }
}
