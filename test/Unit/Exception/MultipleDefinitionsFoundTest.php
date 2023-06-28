<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit\Exception;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Exception;
use Ergebnis\Classy\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\MultipleDefinitionsFound::class)]
#[Framework\Attributes\UsesClass(Construct::class)]
final class MultipleDefinitionsFoundTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testDefaults(): void
    {
        $exception = new Exception\MultipleDefinitionsFound();

        self::assertSame([], $exception->constructs());
    }

    public function testFromConstructsReturnsException(): void
    {
        $name = 'Foo\\Bar\\Baz';

        $fileNames = [
            '/src/Foo/Bar/Baz.php',
            '/test/Fixture/Foo/Bar/Baz.php',
        ];

        $constructs = [
            Construct::fromName($name)->definedIn(...$fileNames),
        ];

        $exception = Exception\MultipleDefinitionsFound::fromConstructs($constructs);

        $format = <<<'PHP'
Multiple definitions have been found for the following constructs:

 - "%s" defined in "%s"
PHP;

        $message = \sprintf(
            $format,
            $name,
            \implode('", "', $fileNames),
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($constructs, $exception->constructs());
    }
}
