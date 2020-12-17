<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\CacheStatusAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class CacheStatusAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CacheStatusAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_with_default_file_driver()
    {
        $this->runEnlightn();

        $this->assertPassed(CacheStatusAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_non_existent_storage_path()
    {
        $this->app->config->set('cache.default', 'memcached');

        $this->runEnlightn();

        $this->assertFailed(CacheStatusAnalyzer::class);
    }
}
