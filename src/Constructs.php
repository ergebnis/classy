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

namespace Localheinz\Classy;

use Localheinz\Token\Sequence;

final class Constructs
{
    /**
     * Returns an array of names of classy constructs (classes, interfaces, traits) found in source.
     *
     * @param string $source
     *
     * @return Construct[]
     */
    public static function fromSource(string $source): array
    {
        $constructs = [];

        $sequence = Sequence::fromSource($source);
        $count = \count($sequence);
        $namespacePrefix = '';

        for ($index = 0; $index < $count; ++$index) {
            $token = $sequence->at($index);

            // collect namespace name
            if ($token->isType(T_NAMESPACE)) {
                $namespaceSegments = [];

                for ($index = $sequence->significantAfter($index)->index(); $index < $count; ++$index) {
                    $token = $sequence->at($index);

                    if (!$token->isType(T_STRING)) {
                        continue;
                    }

                    if ($token->isContent('{', ';')) {
                        break;
                    }

                    $namespaceSegments[] = $token->content();
                }

                $namespace = \implode('\\', $namespaceSegments);
                $namespacePrefix = $namespace . '\\';
            }

            // skip non-classy tokens
            if (!$token->isType(T_CLASS, T_INTERFACE, T_TRAIT)) {
                continue;
            }

            // skip anonymous classes
            if ($token->isType(T_CLASS) && $sequence->significantBefore($index)->isType(T_NEW)) {
                continue;
            }

            // collect construct name
            $token = $sequence->significantAfter($index);

            $constructs[] = Construct::fromName($namespacePrefix . $token->content());
        }

        \usort($constructs, function (Construct $a, Construct $b) {
            return \strcmp(
                $a->name(),
                $b->name()
            );
        });

        return $constructs;
    }
}
