<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Exception;

final class ParseError extends \ParseError implements ExceptionInterface
{
    public static function fromParseError(\ParseError $exception): self
    {
        return new self(
            $exception->getMessage(),
            0,
            $exception
        );
    }

    public static function fromFileNameAndParseError(string $fileName, \ParseError $exception): self
    {
        return new self(
            \sprintf(
                'A parse error occurred when parsing "%s": "%s".',
                $fileName,
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }
}
