<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Security\AppDebugAnalyzer;

class AppDebugAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(AppDebugAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_app_debug_in_production()
    {
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);

        $this->runEnlightn();

        $this->assertFailedAt(AppDebugAnalyzer::class, $this->getConfigStubPath('app'), 42);
    }

    /**
     * @test
     */
    public function passes_with_app_debug_in_local()
    {
        $this->app->config->set('app.env', 'local');
        $this->app->config->set('app.debug', true);

        $this->runEnlightn();

        $this->assertPassed(AppDebugAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_without_app_debug()
    {
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', false);

        $this->runEnlightn();

        $this->assertPassed(AppDebugAnalyzer::class);
    }
}
