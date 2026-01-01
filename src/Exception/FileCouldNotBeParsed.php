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

namespace Ergebnis\Classy\Exception;

use Ergebnis\Classy\FilePath;

final class FileCouldNotBeParsed extends \RuntimeException implements Exception
{
    public static function atFilePathWithParseError(
        FilePath $filePath,
        \ParseError $parseError
    ): self {
        return new self(
            \sprintf(
                'File "%s" could not be parsed because of a parse error with message "%s".',
                $filePath->toString(),
                $parseError->getMessage(),
            ),
            0,
            $parseError,
        );
    }
}
