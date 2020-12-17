<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\LoginThrottlingAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\LoginThrottlingAnonymousInstanceStub;
use Enlightn\Enlightn\Tests\Stubs\LoginThrottlingFacadeStub;
use Enlightn\Enlightn\Tests\Stubs\LoginThrottlingInstanceStub;
use Enlightn\Enlightn\Tests\Stubs\LoginThrottlingSubClassInstanceStub;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Mockery as m;

class LoginThrottlingAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $router = $app->make(Router::class);
        $kernel = $app->make(Kernel::class);

        $analyzer = m::mock(LoginThrottlingAnalyzer::class, [$router, $kernel])->makePartial();

        $analyzer->shouldReceive('skip')->andReturn(false);

        $this->setupEnvironmentFor(LoginThrottlingAnalyzer::class, $app, $analyzer);
    }

    /**
     * @test
     */
    public function passes_for_rate_limiter_facade_usage()
    {
        $this->setBasePathFrom(LoginThrottlingFacadeStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerUnprotectedLoginRoute();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_rate_limiter_instance_usage()
    {
        $this->setBasePathFrom(LoginThrottlingInstanceStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerUnprotectedLoginRoute();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_rate_limiter_anonymous_instance_usage()
    {
        $this->setBasePathFrom(LoginThrottlingAnonymousInstanceStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerUnprotectedLoginRoute();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_rate_limiter_subclass_instance_usage()
    {
        $this->setBasePathFrom(LoginThrottlingSubClassInstanceStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerUnprotectedLoginRoute();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_unprotected_route()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerUnprotectedLoginRoute();

        $this->runEnlightn();

        $this->assertFailed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_protected_route()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerProtectedLoginRoute();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_aliased_middleware_protected_route()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->registerStatefulGlobalMiddleware();

        $this->registerProtectedLoginRouteWithNamedMiddleware();

        $this->runEnlightn();

        $this->assertPassed(LoginThrottlingAnalyzer::class);
    }

    protected function registerProtectedLoginRoute()
    {
        Route::post('/login', function () {
            return 'Go away brute force attacker';
        })->middleware(ThrottleRequests::class.':60,1');
    }

    protected function registerProtectedLoginRouteWithNamedMiddleware()
    {
        $this->registerRouteMiddlewareAlias('throttle', ThrottleRequests::class);

        Route::post('/login', function () {
            return 'Bring it on';
        })->middleware('throttle:60,1');
    }

    protected function registerUnprotectedLoginRoute()
    {
        Route::post('/login', function () {
            return 'Exploit me';
        });
    }
}
