<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017-2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/classy
 */

namespace Ergebnis\Classy\Test\Unit\Exception;

use Ergebnis\Classy\Construct;
use Ergebnis\Classy\Exception\ExceptionInterface;
use Ergebnis\Classy\Exception\MultipleDefinitionsFound;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Classy\Exception\MultipleDefinitionsFound
 *
 * @uses \Ergebnis\Classy\Construct
 */
final class MultipleDefinitionsFoundTest extends Framework\TestCase
{
    use Helper;

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

        $exception = MultipleDefinitionsFound::fromConstructs($constructs);

        self::assertInstanceOf(MultipleDefinitionsFound::class, $exception);
        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertInstanceOf(ExceptionInterface::class, $exception);

        $format = <<<'PHP'
Multiple definitions have been found for the following constructs:

 - "%s" defined in "%s"
PHP;

        $message = \sprintf(
            $format,
            $name,
            \implode('", "', $fileNames)
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($constructs, $exception->constructs());
    }
}
