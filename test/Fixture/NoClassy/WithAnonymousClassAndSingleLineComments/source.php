<?php

namespace Ergebnis\Classy\Test\Fixture\NoClassy\WithAnonymousClassAndSingleLineComments;

function foo()
{
    return // foo
    new // bar
    class() // baz
    extends // qux
    \stdClass {};
}
