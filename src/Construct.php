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

namespace Ergebnis\Classy;

interface Construct
{
    /**
     * Returns the name of the construct.
     */
    public function name(): Name;

    /**
     * Returns the type of the construct.
     */
    public function type(): Type;
}
