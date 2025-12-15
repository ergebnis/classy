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

namespace Ergebnis\Classy\Collector;

use Ergebnis\Classy\ConstructFromSource;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Name;
use Ergebnis\Classy\Type;

final class TokenGetAllConstructFromSourceCollector implements ConstructFromSourceCollector
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
     * @var array<int, Type>
     */
    private array $classyTokenKindToType;

    public function __construct()
    {
        $classyTokenKindToType = [
            \T_CLASS => Type::class(),
            \T_INTERFACE => Type::interface(),
            \T_TRAIT => Type::trait(),
        ];

        /**
         * @see https://wiki.php.net/rfc/enumerations
         */
        if (
            \PHP_VERSION_ID >= 80100
            && \defined('T_ENUM')
        ) {
            $classyTokenKindToType = [
                \T_CLASS => Type::class(),
                \T_ENUM => Type::enum(),
                \T_INTERFACE => Type::interface(),
                \T_TRAIT => Type::trait(),
            ];
        }

        $this->classyTokenKindToType = $classyTokenKindToType;
    }

    public function collectFromSource(string $source): array
    {
        try {
            $sequence = \token_get_all(
                $source,
                \TOKEN_PARSE,
            );
        } catch (\ParseError $parseError) {
            throw Exception\SourceCouldNotBeParsed::fromParseError($parseError);
        }

        $namespacePrefix = '';

        $namespaceSegmentOrNamespaceTokenKinds = [
            \T_STRING,
        ];

        $constructs = [];

        $count = \count($sequence);

        /**
         * @see https://wiki.php.net/rfc/namespaced_names_as_token
         */
        if (
            \PHP_VERSION_ID >= 80000
            && \defined('T_NAME_QUALIFIED')
        ) {
            $namespaceSegmentOrNamespaceTokenKinds = [
                \T_STRING,
                \T_NAME_QUALIFIED,
            ];
        }

        for ($index = 0; $index < $count; ++$index) {
            $token = $sequence[$index];

            // collect namespace name
            if (\is_array($token) && \T_NAMESPACE === $token[0]) {
                $namespaceSegments = [];

                // collect namespace segments
                for ($index = self::significantTokenIndexAfter($index, $sequence, $count); $index < $count; ++$index) {
                    $token = $sequence[$index];

                    if (\is_array($token) && !\in_array($token[0], $namespaceSegmentOrNamespaceTokenKinds, true)) {
                        continue;
                    }

                    $content = self::content($token);

                    if (\in_array($content, ['{', ';'], true)) {
                        break;
                    }

                    $namespaceSegments[] = $content;
                }

                $namespace = \implode('\\', $namespaceSegments);
                $namespacePrefix = $namespace . '\\';

                continue;
            }

            if (!\is_array($token)) {
                continue;
            }

            if (!\array_key_exists($token[0], $this->classyTokenKindToType)) {
                continue;
            }

            $type = $this->classyTokenKindToType[$token[0]];

            // skip anonymous classes
            if (\T_CLASS === $token[0]) {
                $current = self::significantTokenIndexBefore(
                    $index,
                    $sequence,
                );

                $token = $sequence[$current];

                // if significant token before T_CLASS is T_NEW, it's an instantiation of an anonymous class
                if (\is_array($token) && \T_NEW === $token[0]) {
                    continue;
                }
            }

            $index = self::significantTokenIndexAfter(
                $index,
                $sequence,
                $count,
            );

            $token = $sequence[$index];

            $constructs[] = ConstructFromSource::create(
                Name::fromString($namespacePrefix . self::content($token)),
                $type,
            );
        }

        return $constructs;
    }

    /**
     * Returns the index of the significant token after the index.
     *
     * @param array<int, array{0: int, 1: string, 2: int}|string> $sequence
     */
    private static function significantTokenIndexAfter(
        int $index,
        array $sequence,
        int $count
    ): int {
        for ($current = $index + 1; $current < $count; ++$current) {
            $token = $sequence[$current];

            if (\is_array($token) && \in_array($token[0], self::$insignificantTokenKinds, true)) {
                continue;
            }

            return $current;
        }

        throw Exception\ShouldNotHappen::create();
    }

    /**
     * Returns the index of the significant token after the index.
     *
     * @param array<int, array{0: int, 1: string, 2: int}|string> $sequence
     */
    private static function significantTokenIndexBefore(
        int $index,
        array $sequence
    ): int {
        for ($current = $index - 1; -1 < $current; --$current) {
            $token = $sequence[$current];

            if (\is_array($token) && \in_array($token[0], self::$insignificantTokenKinds, true)) {
                continue;
            }

            return $current;
        }

        throw Exception\ShouldNotHappen::create();
    }

    /**
     * Returns the string content of a token.
     *
     * @param array{0: int, 1: string, 2: int}|string $token
     */
    private static function content($token): string
    {
        if (\is_array($token)) {
            return $token[1];
        }

        return $token;
    }
}
