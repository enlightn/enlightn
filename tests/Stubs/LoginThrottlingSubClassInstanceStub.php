<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class LoginThrottlingSubClassInstanceStub
{
    public function dummyFunction()
    {
        $limiter = new DummyLimiter(cache()->store());

        $limiter->hit('somekey');
    }
}
