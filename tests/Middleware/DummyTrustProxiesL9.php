<?php

namespace Enlightn\Enlightn\Tests\Middleware;

use Illuminate\Http\Middleware\TrustProxies;

class DummyTrustProxiesL9 extends TrustProxies
{
    /**
     * The trusted proxies for the application.
     *
     * @var null|string|array
     */
    protected $proxies = '*';
}