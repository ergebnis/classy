<?php

namespace Ergebnis\Classy\Test\Fixture\NoClassy\WithAnonymousClass;

function foo()
{
    return new class() extends \stdClass {};
}
