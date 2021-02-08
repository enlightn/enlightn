<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class DeadCodeStub
{
    public function deadCodeTest()
    {
        return "return fast!";
        $x = 5;
    }

    public function noOpTest()
    {
        $x = 10;
        $x;
    }
}

function (array $a) {
    if (count($a) === 0) {
        // genius, do a for loop for an empty array!
        foreach ($a as $val) {
        }
    }
};

class IAmADeadCodeMaster
{
    private const FOO_CONST = 1;

    private const UNUSED_CONST = 2;

    public function doFoo()
    {
        echo self::FOO_CONST;
    }

    private static function unusedPrivateStaticMethod()
    {
    }

    private function unusedPrivateMethod()
    {
    }
}
