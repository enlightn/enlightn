<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Performance\CacheDriverAnalyzer;

class CacheDriverAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CacheDriverAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_null_cache_driver()
    {
        $this->app->config->set('cache.default', 'null');

        $this->runEnlightn();

        $this->assertFailedAt(CacheDriverAnalyzer::class, $this->getConfigStubPath('cache'), 18);
    }

    /**
     * @test
     */
    public function detects_array_cache_driver()
    {
        $this->app->config->set('cache.default', 'array');

        $this->runEnlightn();

        $this->assertFailedAt(CacheDriverAnalyzer::class, $this->getConfigStubPath('cache'), 18);
    }

    /**
     * @test
     */
    public function detects_file_cache_driver_in_non_local_env()
    {
        $this->app->config->set('cache.default', 'file');
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailedAt(CacheDriverAnalyzer::class, $this->getConfigStubPath('cache'), 18);
    }

    /**
     * @test
     */
    public function passes_file_cache_driver_in_local_env()
    {
        $this->app->config->set('cache.default', 'file');
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(CacheDriverAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_database_cache_driver_in_non_local_env()
    {
        $this->app->config->set('cache.default', 'database');
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailedAt(CacheDriverAnalyzer::class, $this->getConfigStubPath('cache'), 18);
    }

    /**
     * @test
     */
    public function passes_database_cache_driver_in_local_env()
    {
        $this->app->config->set('cache.default', 'database');
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(CacheDriverAnalyzer::class);
    }
}
