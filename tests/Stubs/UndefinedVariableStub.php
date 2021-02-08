<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class UndefinedVariableStub
{
    public function foo()
    {
        if ($definedLater) { // undefined
            $definedLater = 1;
        }

        switch (foo()) {
            case 1:
                $definedInCases = foo();

                break;
            case 2:
                $definedInCases = 5;

                break;
        }

        $definedInCases->foo(); // might be undefined
    }
}

function foo()
{
    return 3;
}
