<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Cache\RateLimiter;

class LoginThrottlingAnonymousInstanceStub
{
    public function dummyFunction()
    {
        (new RateLimiter(cache()->store()))->hit('somekey');
    }
}
