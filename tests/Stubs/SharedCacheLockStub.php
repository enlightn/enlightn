<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Support\Facades\Cache as MyCache;

class SharedCacheLockStub
{
    public function aliasLockTest()
    {
        MyCache::lock('test', 10);
    }

    public function fqnLockTest()
    {
        \Illuminate\Support\Facades\Cache::lock('test');
    }

    public function optimalLockTest()
    {
        MyCache::store('locks')->lock('test', 10);
    }
}
