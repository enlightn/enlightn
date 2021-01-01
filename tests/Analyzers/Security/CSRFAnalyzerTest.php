<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\CSRFAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;

class CSRFAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CSRFAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skips_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(CSRFAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_global_csrf_middleware()
    {
        $this->clearMiddlewareGroups();
        $this->registerStatefulGlobalMiddleware();
        $this->registerGlobalCsrfMiddleware();

        $this->runEnlightn();

        $this->assertPassed(CSRFAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_web_group_middleware()
    {
        $this->clearMiddlewareGroups();
        $this->registerStatefulGlobalMiddleware();
        $this->registerGroupMiddleware('web', AppVerifyCsrfToken::class);

        $this->runEnlightn();

        $this->assertPassed(CSRFAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_post_routes_with_individual_csrf_protection()
    {
        $this->clearMiddlewareGroups();
        $this->registerStatefulGlobalMiddleware();
        $this->registerProtectedRoute();

        $this->runEnlightn();

        $this->assertPassed(CSRFAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_post_routes_without_protection()
    {
        $this->clearMiddlewareGroups();
        $this->registerStatefulGlobalMiddleware();
        $this->registerUnprotectedRoute();

        $this->runEnlightn();

        $this->assertFailed(CSRFAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_some_routes_without_protection()
    {
        $this->clearMiddlewareGroups();
        $this->registerStatefulGlobalMiddleware();
        $this->registerProtectedRoute();
        $this->registerUnprotectedRoute();

        $this->runEnlightn();

        $this->assertFailed(CSRFAnalyzer::class);
    }

    protected function registerGlobalCsrfMiddleware()
    {
        $this->app->make(Kernel::class)->pushMiddleware(AppVerifyCsrfToken::class);
    }

    protected function registerProtectedRoute()
    {
        Route::post('/i-am-secure', function () {
            return 'Go away CSRF attacker';
        })->middleware(AppVerifyCsrfToken::class);
    }

    protected function registerUnprotectedRoute()
    {
        Route::post('/i-am-insecure', function () {
            return 'Exploit me';
        });
    }
}

class AppVerifyCsrfToken extends VerifyCsrfToken
{
}
