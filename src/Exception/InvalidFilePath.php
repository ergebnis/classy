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

final class InvalidFilePath extends \InvalidArgumentException implements Exception
{
    public static function blankOrEmpty(): self
    {
        return new self('File path can not be blank or empty.');
    }
}
