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

namespace Localheinz\Classy\Test\Unit;

use Localheinz\Classy\Construct;
use Localheinz\Test\Util\Helper;
use PHPUnit\Framework;

final class ConstructTest extends Framework\TestCase
{
    use Helper;

    public function testFromNameReturnsConstruct(): void
    {
        $name = $this->faker()->word;

        $construct = Construct::fromName($name);

        $this->assertInstanceOf(Construct::class, $construct);
        $this->assertSame($name, $construct->name());
    }

    public function testDefaults(): void
    {
        $construct = Construct::fromName($this->faker()->word);

        $this->assertInternalType('array', $construct->fileNames());
        $this->assertCount(0, $construct->fileNames());
    }

    public function testToStringReturnsName(): void
    {
        $name = $this->faker()->word;

        $construct = Construct::fromName($name);

        $this->assertSame($name, $construct->__toString());
    }

    public function testDefinedInClonesInstanceAndAddsFileNames(): void
    {
        $faker = $this->faker();

        $name = $faker->word;

        $fileNames = \array_map(function () use ($faker) {
            return \sprintf(
                '%s.%s',
                $faker->word,
                $faker->fileExtension
            );
        }, \array_fill(0, 5, null));

        $construct = Construct::fromName($name);

        $mutated = $construct->definedIn(...$fileNames);

        $this->assertInstanceOf(Construct::class, $mutated);
        $this->assertNotSame($construct, $mutated);
        $this->assertSame($name, $mutated->name());
        $this->assertInternalType('array', $mutated->fileNames());
        $this->assertCount(\count($fileNames), $mutated->fileNames());
        $this->assertArraySubset($fileNames, $mutated->fileNames());
    }
}
