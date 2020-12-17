<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class MissingReturnStub
{
    public function doFoo(): int
    {
    }

    public function doBaz(): int
    {
        $this->doFoo();
    }
}
