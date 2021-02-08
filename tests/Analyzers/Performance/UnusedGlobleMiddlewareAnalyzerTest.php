<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\UnusedGlobalMiddlewareAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Middleware\TrustHosts;

class UnusedGlobleMiddlewareAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(UnusedGlobalMiddlewareAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_with_no_global_middleware()
    {
        $this->runEnlightn();

        $this->assertPassed(UnusedGlobalMiddlewareAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_trusted_hosts_without_trusted_proxies()
    {
        $this->app->make(Kernel::class)->pushMiddleware(TrustHosts::class);

        $this->runEnlightn();

        $this->assertFailed(UnusedGlobalMiddlewareAnalyzer::class);
    }
}
