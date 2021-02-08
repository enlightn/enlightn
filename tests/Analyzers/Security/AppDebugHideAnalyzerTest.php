<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\AppDebugHideAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class AppDebugHideAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(
            AppDebugHideAnalyzer::class,
            $app,
            $this->getMockedAnalyzer(AppDebugHideAnalyzer::class)
        );
    }

    /**
     * @test
     */
    public function detects_empty_app_debug_hide_in_staging()
    {
        $this->app->config->set('app.env', 'staging');
        $this->app->config->set('app.debug', true);

        $this->runEnlightn();

        $this->assertFailedAt(AppDebugHideAnalyzer::class, $this->getConfigStubPath('app'), 42);
    }

    /**
     * @test
     */
    public function passes_with_debug_mode_off()
    {
        $this->app->config->set('app.debug', false);

        $this->runEnlightn();

        $this->assertPassed(AppDebugHideAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_non_empty_debug_hide()
    {
        $this->app->config->set('app.env', 'staging');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('app.debug_hide', ['_ENV' => ['APP_KEY']]);

        $this->runEnlightn();

        $this->assertPassed(AppDebugHideAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_non_empty_debug_blacklist()
    {
        $this->app->config->set('app.env', 'staging');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('app.debug_blacklist', ['_ENV' => ['APP_KEY']]);

        $this->runEnlightn();

        $this->assertPassed(AppDebugHideAnalyzer::class);
    }
}
