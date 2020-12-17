<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Support\Facades\RateLimiter;

class LoginThrottlingFacadeStub
{
    public function dummyFunction()
    {
        RateLimiter::hit('somekey');
    }
}
