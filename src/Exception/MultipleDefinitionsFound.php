<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Exception;

use Localheinz\Classy\Construct;

final class MultipleDefinitionsFound extends \RuntimeException implements ExceptionInterface
{
    /**
     * @var Construct[]
     */
    private $constructs;

    /**
     * Returns a new exception from constructs.
     *
     * @param Construct[] $constructs
     *
     * @return self
     */
    public static function fromConstructs(array $constructs): self
    {
        $exception = new self(\sprintf(
            "Multiple definitions have been found for the following constructs:\n\n%s",
            \implode("\n", \array_map(static function (Construct $construct) {
                return \sprintf(
                    ' - "%s" defined in "%s"',
                    $construct->name(),
                    \implode('", "', $construct->fileNames())
                );
            }, $constructs))
        ));

        $exception->constructs = $constructs;

        return $exception;
    }

    /**
     * Returns an array of constructs.
     *
     * @return Construct[]
     */
    public function constructs(): array
    {
        return $this->constructs;
    }
}
