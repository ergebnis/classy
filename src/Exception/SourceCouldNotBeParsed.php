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

final class SourceCouldNotBeParsed extends \RuntimeException implements Exception
{
    public static function withParseError(\ParseError $parseError): self
    {
        return new self(
            \sprintf(
                'Source not be parsed because of a parse error with message "%s".',
                $parseError->getMessage(),
            ),
            0,
            $parseError,
        );
    }
}
