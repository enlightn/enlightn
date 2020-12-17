<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class InvalidFunctionCallStub
{
    private function foo()
    {
        someNonExistentFunction();
        request()->input('somevar'); // existing function
        bar(1);
    }
}

function bar($foo, $bar)
{

}
