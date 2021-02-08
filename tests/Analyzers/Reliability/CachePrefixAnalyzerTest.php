<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\CachePrefixAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class CachePrefixAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CachePrefixAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_empty_cache_prefix()
    {
        $this->app->config->set('cache.prefix', '');

        $this->runEnlightn();

        $this->assertFailedAt(CachePrefixAnalyzer::class, $this->getConfigStubPath('cache'), 102);
    }

    /**
     * @test
     */
    public function detects_generic_cache_prefix()
    {
        $this->app->config->set('cache.prefix', 'laravel_cache');

        $this->runEnlightn();

        $this->assertFailedAt(CachePrefixAnalyzer::class, $this->getConfigStubPath('cache'), 102);
    }

    /**
     * @test
     */
    public function passes_with_specific_cache_prefix()
    {
        $this->app->config->set('cache.prefix', 'enlightn_cache');

        $this->runEnlightn();

        $this->assertPassed(CachePrefixAnalyzer::class);
    }
}
