<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\SessionDriverAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

class SessionDriverAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(SessionDriverAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skipped_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(SessionDriverAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_null_session_driver()
    {
        $this->app->config->set('session.driver', 'null');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertFailedAt(SessionDriverAnalyzer::class, $this->getConfigStubPath('session'), 12);
    }

    /**
     * @test
     */
    public function detects_array_session_driver()
    {
        $this->app->config->set('session.driver', 'array');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertFailedAt(SessionDriverAnalyzer::class, $this->getConfigStubPath('session'), 12);
    }

    /**
     * @test
     */
    public function detects_file_session_driver_in_production()
    {
        $this->app->config->set('session.driver', 'file');
        $this->app->config->set('app.env', 'production');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertFailedAt(SessionDriverAnalyzer::class, $this->getConfigStubPath('session'), 12);
    }

    /**
     * @test
     */
    public function passes_file_session_driver_in_local()
    {
        $this->app->config->set('session.driver', 'file');
        $this->app->config->set('app.env', 'local');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertPassed(SessionDriverAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_cookie_session_driver_in_production()
    {
        $this->app->config->set('session.driver', 'cookie');
        $this->app->config->set('app.env', 'production');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertFailedAt(SessionDriverAnalyzer::class, $this->getConfigStubPath('session'), 12);
    }

    /**
     * @test
     */
    public function passes_cookie_session_driver_in_local()
    {
        $this->app->config->set('session.driver', 'cookie');
        $this->app->config->set('app.env', 'local');

        $this->registerDummyRouteWithSessionMiddleware();

        $this->runEnlightn();

        $this->assertPassed(SessionDriverAnalyzer::class);
    }

    protected function registerDummyRouteWithSessionMiddleware()
    {
        Route::get('/dummy')->middleware(StartSession::class);
    }
}
