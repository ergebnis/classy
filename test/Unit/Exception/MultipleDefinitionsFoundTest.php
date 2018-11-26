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

namespace Localheinz\Classy\Test\Unit\Exception;

use Localheinz\Classy\Construct;
use Localheinz\Classy\Exception\MultipleDefinitionsFound;

final class MultipleDefinitionsFoundTest extends AbstractTestCase
{
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

        $this->assertInstanceOf(MultipleDefinitionsFound::class, $exception);

        $format = <<<'PHP'
Multiple definitions have been found for the following constructs:

 - "%s" defined in "%s"
PHP;

        $message = \sprintf(
            $format,
            $name,
            \implode('", "', $fileNames)
        );

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($constructs, $exception->constructs());
    }
}
