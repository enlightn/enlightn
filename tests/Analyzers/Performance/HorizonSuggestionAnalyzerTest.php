<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\HorizonSuggestionAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class HorizonSuggestionAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(HorizonSuggestionAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skips_for_non_redis_queues()
    {
        $this->runEnlightn();

        $this->assertSkipped(HorizonSuggestionAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_redis_queues_without_horizon()
    {
        $this->app->config->set('queue.default', 'redis');

        $this->runEnlightn();

        $this->assertFailed(HorizonSuggestionAnalyzer::class);
    }
}
