<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Reliability\QueueTimeoutAnalyzer;

class QueueTimeoutAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(QueueTimeoutAnalyzer::class, $app);

        $this->loadConfigFromStub('horizon', $app);
    }

    /**
     * @test
     */
    public function passes_with_defaults()
    {
        $this->runEnlightn();

        $this->assertPassed(QueueTimeoutAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_sqs()
    {
        $this->app->config->set('queue.default', 'sqs');
        $this->app->config->set('queue.connections.sqs.retry_after', 45);

        $this->runEnlightn();

        $this->assertPassed(QueueTimeoutAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_low_retry_after_for_default_queue()
    {
        $this->app->config->set('queue.default', 'redis');
        $this->app->config->set('queue.connections.redis.retry_after', 45);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }

    /**
     * @test
     */
    public function detects_low_retry_after_for_non_default_queue()
    {
        $this->app->config->set('queue.connections.redis.retry_after', 45);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }

    /**
     * @test
     */
    public function detects_horizon_default_timeouts()
    {
        $this->app->config->set('queue.default', 'redis');
        $this->app->config->set('horizon.defaults.supervisor-1.timeout', 90);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }

    /**
     * @test
     */
    public function detects_horizon_environment_specific_timeouts()
    {
        $this->app->config->set('queue.default', 'redis');
        $this->app->config->set('horizon.environments.production.supervisor-1.timeout', 90);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }

    /**
     * @test
     */
    public function detects_horizon_multiple_default_timeouts()
    {
        $this->app->config->set('queue.default', 'redis');
        $this->app->config->set('horizon.defaults.supervisor-1.timeout', 60);
        $this->app->config->set('horizon.defaults.supervisor-2.timeout', 90);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }

    /**
     * @test
     */
    public function detects_horizon_multiple_environment_specific_timeouts()
    {
        $this->app->config->set('queue.default', 'redis');
        $this->app->config->set('horizon.environments.production.supervisor-1.timeout', 60);
        $this->app->config->set('horizon.environments.production.supervisor-2.timeout', 90);

        $this->runEnlightn();

        $this->assertFailedAt(QueueTimeoutAnalyzer::class, $this->getConfigStubPath('queue'), 66);
    }
}
