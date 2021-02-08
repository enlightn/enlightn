<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\MinificationAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Filesystem\Filesystem;

class MinificationAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(MinificationAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skips_for_local_env()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertSkipped(MinificationAnalyzer::class);
    }

    /**
     * @test
     */
    public function skips_for_no_assets_to_minify()
    {
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertSkipped(MinificationAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_unminified_files()
    {
        $this->app->config->set('app.env', 'production');
        (new Filesystem())->copyDirectory(
            $this->getBaseStubPath().DIRECTORY_SEPARATOR.'unminified_assets',
            public_path('unminified_assets')
        );

        $this->runEnlightn();

        $this->assertFailed(MinificationAnalyzer::class);
        $this->assertErrorMessageContains(MinificationAnalyzer::class, 'bootstrap.js');
        $this->assertErrorMessageContains(MinificationAnalyzer::class, 'jquery.js');
        $this->assertErrorMessageContains(MinificationAnalyzer::class, 'bootstrap.css');

        (new Filesystem())->deleteDirectory(public_path('unminified_assets'));
    }

    /**
     * @test
     */
    public function passes_with_minified_assets()
    {
        $this->app->config->set('app.env', 'production');
        (new Filesystem())->copyDirectory($this->getAssetsStubPath(), public_path('assets'));

        $this->runEnlightn();

        $this->assertPassed(MinificationAnalyzer::class);

        (new Filesystem())->deleteDirectory(public_path('assets'));
    }
}
