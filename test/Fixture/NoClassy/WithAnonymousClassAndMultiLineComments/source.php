<?php

namespace Ergebnis\Classy\Test\Fixture\NoClassy\WithAnonymousClassAndMultiLineComments;

function foo()
{
    return /* foo */ new /* bar */ class() /* baz */ extends \stdClass {};
}
