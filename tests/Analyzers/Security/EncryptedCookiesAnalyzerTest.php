<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\EncryptedCookiesAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Support\Facades\Route;

class EncryptedCookiesAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(EncryptedCookiesAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skips_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(EncryptedCookiesAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_encrypt_cookies_middleware()
    {
        $this->registerStatefulGlobalMiddleware();
        $this->registerProtectedRoute();

        $this->runEnlightn();

        $this->assertPassed(EncryptedCookiesAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_missing_encrypt_cookies_middleware()
    {
        $this->registerStatefulGlobalMiddleware();
        $this->registerUnprotectedRoute();

        $this->runEnlightn();

        $this->assertFailed(EncryptedCookiesAnalyzer::class);
    }

    protected function registerProtectedRoute()
    {
        Route::middleware('web')->group(function () {
            Route::get('/test', function () {
                //
            });
        });
        $this->registerGroupMiddleware('web', EncryptCookies::class);
    }

    protected function registerUnprotectedRoute()
    {
        Route::get('/test', function () {
            //
        });
    }
}
