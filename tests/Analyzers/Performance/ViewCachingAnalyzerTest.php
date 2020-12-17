<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Performance\ViewCachingAnalyzer;

class ViewCachingAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(ViewCachingAnalyzer::class, $app);

        $app->config->set('view.paths', [$this->getViewStubPath()]);
    }

    /**
     * @test
     */
    public function detects_non_cached_views_in_production()
    {
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailed(ViewCachingAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_with_cached_views_in_production()
    {
        $this->app->config->set('app.env', 'production');

        $this->artisan('view:cache');

        $this->runEnlightn();

        $this->assertPassed(ViewCachingAnalyzer::class);

        $this->artisan('view:clear');
    }

    /**
     * @test
     */
    public function detects_cached_views_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->artisan('view:cache');

        $this->runEnlightn();

        $this->assertFailed(ViewCachingAnalyzer::class);

        $this->artisan('view:clear');
    }

    /**
     * @test
     */
    public function passes_with_non_cached_views_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(ViewCachingAnalyzer::class);
    }
}
