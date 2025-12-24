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

use Ergebnis\Classy\FilePath;

final class FileCouldNotBeParsed extends \RuntimeException implements Exception
{
    public static function fromFileAndParseError(
        FilePath $filePath,
        \ParseError $parseError
    ): self {
        return new self(
            \sprintf(
                'File "%s" could not be read.',
                $filePath->toString(),
            ),
            0,
            $parseError,
        );
    }
}
