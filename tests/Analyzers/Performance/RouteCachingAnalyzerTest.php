<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\RouteCachingAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class RouteCachingAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(RouteCachingAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_cached_routes_in_local()
    {
        $this->app->config->set('app.env', 'local');
        
        touch($this->app->getCachedRoutesPath());

        $this->runEnlightn();

        $this->assertFailed(RouteCachingAnalyzer::class);
        
        unlink($this->app->getCachedRoutesPath());
    }

    /**
     * @test
     */
    public function detects_non_cached_routes_in_production()
    {
        $this->app->config->set('app.env', 'production');
        
        $this->runEnlightn();

        $this->assertFailed(RouteCachingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_cached_routes_in_production()
    {
        $this->app->config->set('app.env', 'production');
        
        touch($this->app->getCachedRoutesPath());

        $this->runEnlightn();

        $this->assertPassed(RouteCachingAnalyzer::class);

        unlink($this->app->getCachedRoutesPath());
    }

    /**
     * @test
     */
    public function passes_non_cached_routes_in_local()
    {
        $this->app->config->set('app.env', 'local');
        
        $this->runEnlightn();

        $this->assertPassed(RouteCachingAnalyzer::class);
    }
}
