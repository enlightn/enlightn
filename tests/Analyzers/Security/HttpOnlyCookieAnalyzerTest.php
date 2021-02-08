<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\HttpOnlyCookieAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;

class HttpOnlyCookieAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(HttpOnlyCookieAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_no_http_only()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->config->set('session.http_only', false);

        $this->runEnlightn();

        $this->assertFailedAt(HttpOnlyCookieAnalyzer::class, $this->getConfigStubPath('session'), 184);
    }

    /**
     * @test
     */
    public function passes_with_http_only()
    {
        $this->registerStatefulGlobalMiddleware();

        $this->app->config->set('session.http_only', true);

        $this->runEnlightn();

        $this->assertPassed(HttpOnlyCookieAnalyzer::class);
    }

    /**
     * @test
     */
    public function skips_for_stateless_apps()
    {
        $this->runEnlightn();

        $this->assertSkipped(HttpOnlyCookieAnalyzer::class);
    }
}
