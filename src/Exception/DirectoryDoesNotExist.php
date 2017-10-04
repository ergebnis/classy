<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/classy
 */

namespace Localheinz\Classy\Exception;

final class DirectoryDoesNotExist extends \InvalidArgumentException
{
    /**
     * @var string
     */
    private $directory;

    /**
     * Returns a new exception from a directory.
     *
     * @param string $directory
     *
     * @return self
     */
    public static function fromDirectory(string $directory): self
    {
        $exception = new self(\sprintf(
            'Directory "%s" does not exist.',
            $directory
        ));

        $exception->directory = $directory;

        return $exception;
    }

    /**
     * Returns the directory.
     *
     * @return string
     */
    public function directory(): string
    {
        return $this->directory;
    }
}
