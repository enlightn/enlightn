<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class ForeachIterableStub
{
    public function foo()
    {
        $string = 'foo';
        foreach ($string as $x) {
            //
        }

        $arrayOrFalse = [1, 2, 3];
        if (doFoo()) {
            $arrayOrFalse = false;
        }

        foreach ($arrayOrFalse as $val) {
            //
        }
    }
}

function doFoo()
{
    return true;
}

