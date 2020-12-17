<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Security\HttpOnlyCookieAnalyzer;

class HttpOnlyCookieAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(HttpOnlyCookieAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_no_http_only_in_production()
    {
        $this->app->config->set('session.http_only', false);
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailedAt(HttpOnlyCookieAnalyzer::class, $this->getConfigStubPath('session'), 184);
    }

    /**
     * @test
     */
    public function passes_with_http_only_in_production()
    {
        $this->app->config->set('session.http_only', true);
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertPassed(HttpOnlyCookieAnalyzer::class);
    }

    /**
     * @test
     */
    public function skips_in_local_env()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertSkipped(HttpOnlyCookieAnalyzer::class);
    }
}
