<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class InvalidMethodOverrideStub
{
    public function doBar()
    {
    }
}

class ChildInvalidMethodOverrideStub extends InvalidMethodOverrideStub
{
    public function doBar(string $s) // does not match parent signature
    {
    }
}
