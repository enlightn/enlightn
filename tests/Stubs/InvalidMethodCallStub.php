<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class InvalidMethodCallStub
{
    private function foo()
    {
        $this->protectedMethodFromChild();
        $this->loremipsum(); // nonexistent
    }

    public function ipsum()
    {
    }

    public function test($bar)
    {
    }

    private static function privateStatic()
    {
    }
}

class ChildInvalidMethodCallStub extends InvalidMethodCallStub
{
    protected function protectedMethodFromChild()
    {
        $this->foo(); // private from an ancestor
        $this->test(); // missing parameter

        foreach ($this->returnsVoid() as $void) { // returns void
        }
    }

    public static function staticTest()
    {
        InvalidMethodCallStub::bar(); // non-existent
        InvalidMethodCallStub::ipsum(); // instance
        InvalidMethodCallStub::privateStatic(); // private access
    }

    /**
     * @return void
     */
    private function returnsVoid()
    {
    }
}
