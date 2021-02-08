<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Performance\DebugLogAnalyzer;

class DebugLogAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(DebugLogAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_with_critical_log_in_production()
    {
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('logging.default', 'slack');

        $this->runEnlightn();

        $this->assertPassed(DebugLogAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_debug_log_in_production()
    {
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('logging.default', 'single');

        $this->runEnlightn();

        $this->assertFailedAt(DebugLogAnalyzer::class, $this->getConfigStubPath('logging'), 47);
    }

    /**
     * @test
     */
    public function detects_stack_channel_debug_log_in_production()
    {
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('logging.default', 'stack');
        $this->app->config->set('logging.channels.stack.channels', ['single', 'daily', 'slack']);

        $this->runEnlightn();

        $this->assertFailedAt(DebugLogAnalyzer::class, $this->getConfigStubPath('logging'), 47);
        $this->assertFailedAt(DebugLogAnalyzer::class, $this->getConfigStubPath('logging'), 53);
        $this->assertHasErrors(DebugLogAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_with_local()
    {
        $this->app->config->set('app.env', 'local');
        $this->app->config->set('app.debug', true);

        $this->runEnlightn();

        $this->assertPassed(DebugLogAnalyzer::class);
    }
}
