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

final class FileDoesNotExist extends \InvalidArgumentException implements ExceptionInterface
{
    public static function at(string $file): self
    {
        return new self(\sprintf(
            'File "%s" does not exist.',
            $file,
        ));
    }
}
