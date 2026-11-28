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

namespace Ergebnis\Classy\Collector;

use Ergebnis\Classy;

final class PhpTokenTokenizeConstructFromSourceCollector implements ConstructFromSourceCollector
{
    /**
     * @var list<int>
     */
    private static array $insignificantTokenKinds = [
        \T_COMMENT,
        \T_DOC_COMMENT,
        \T_WHITESPACE,
    ];

    /**
     * @var array<int, Classy\Type>
     */
    private array $classyTokenKindToType;

    public function __construct()
    {
        if (\PHP_VERSION_ID <= 80000) {
            throw Classy\Exception\ParsingNotSupported::php80Required();
        }

        $classyTokenKindToType = [
            \T_CLASS => Classy\Type::class(),
            \T_INTERFACE => Classy\Type::interface(),
            \T_TRAIT => Classy\Type::trait(),
        ];

        /**
         * @see https://wiki.php.net/rfc/enumerations
         */
        if (
            \PHP_VERSION_ID >= 80100
            && \defined('T_ENUM')
        ) {
            $classyTokenKindToType = [
                \T_CLASS => Classy\Type::class(),
                \T_ENUM => Classy\Type::enum(),
                \T_INTERFACE => Classy\Type::interface(),
                \T_TRAIT => Classy\Type::trait(),
            ];
        }

        $this->classyTokenKindToType = $classyTokenKindToType;
    }

    /**
     * @return list<Classy\ConstructFromSource>
     */
    public function collectFromSource(Classy\Source $source): array
    {
        try {
            $sequence = \PhpToken::tokenize(
                $source->toString(),
                \TOKEN_PARSE,
            );
        } catch (\ParseError $parseError) {
            throw Classy\Exception\SourceCouldNotBeParsed::withParseError($parseError);
        }

        $namespacePrefix = '';

        $constructs = [];

        $count = \count($sequence);

        for ($index = 0; $index < $count; ++$index) {
            $token = $sequence[$index];

            // collect namespace name
            if ($token->is(\T_NAMESPACE)) {
                $namespaceSegments = [];

                // collect namespace segments
                for ($index = self::significantTokenIndexAfter($index, $sequence, $count); $index < $count; ++$index) {
                    $token = $sequence[$index];

                    if (!$token->is([\T_STRING, \T_NAME_QUALIFIED])) {
                        continue;
                    }

                    $namespaceSegments[] = $token->text;

                    break;
                }

                $namespace = \implode('\\', $namespaceSegments);
                $namespacePrefix = $namespace . '\\';

                continue;
            }

            if (!$token->is(\array_keys($this->classyTokenKindToType))) {
                continue;
            }

            $type = $this->classyTokenKindToType[$token->id];

            // skip anonymous classes
            if ($token->is(\T_CLASS)) {
                $current = self::significantTokenIndexBefore(
                    $index,
                    $sequence,
                );

                $token = $sequence[$current];

                // if significant token before T_CLASS is T_NEW, it's an instantiation of an anonymous class
                if ($token->is(\T_NEW)) {
                    continue;
                }
            }

            $index = self::significantTokenIndexAfter(
                $index,
                $sequence,
                $count,
            );

            $token = $sequence[$index];

            $constructs[] = Classy\ConstructFromSource::create(
                Classy\Name::fromString($namespacePrefix . $token->text),
                $type,
            );
        }

        return $constructs;
    }

    /**
     * Returns the index of the significant token after the index.
     *
     * @param list<\PhpToken> $sequence
     */
    private static function significantTokenIndexAfter(
        int $index,
        array $sequence,
        int $count
    ): int {
        for ($current = $index + 1; $current < $count; ++$current) {
            $token = $sequence[$current];

            if ($token->is(self::$insignificantTokenKinds)) {
                continue;
            }

            return $current;
        }

        throw Classy\Exception\ShouldNotHappen::create();
    }

    /**
     * Returns the index of the significant token after the index.
     *
     * @param list<\PhpToken> $sequence
     */
    private static function significantTokenIndexBefore(
        int $index,
        array $sequence
    ): int {
        for ($current = $index - 1; -1 < $current; --$current) {
            $token = $sequence[$current];

            if ($token->is(self::$insignificantTokenKinds)) {
                continue;
            }

            return $current;
        }

        throw Classy\Exception\ShouldNotHappen::create();
    }
}
