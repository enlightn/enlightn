<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\ConfigCachingAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class ConfigCachingAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(ConfigCachingAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_cached_config_in_local()
    {
        $this->app->config->set('app.env', 'local');

        touch($this->app->getCachedConfigPath());

        $this->runEnlightn();

        $this->assertFailed(ConfigCachingAnalyzer::class);

        unlink($this->app->getCachedConfigPath());
    }

    /**
     * @test
     */
    public function detects_non_cached_config_in_production()
    {
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailed(ConfigCachingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_cached_config_in_production()
    {
        $this->app->config->set('app.env', 'production');

        touch($this->app->getCachedConfigPath());

        $this->runEnlightn();

        $this->assertPassed(ConfigCachingAnalyzer::class);

        unlink($this->app->getCachedConfigPath());
    }

    /**
     * @test
     */
    public function passes_non_cached_config_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(ConfigCachingAnalyzer::class);
    }
}
