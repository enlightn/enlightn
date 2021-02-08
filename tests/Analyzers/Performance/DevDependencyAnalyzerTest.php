<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\DevDependencyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;

class DevDependencyAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(DevDependencyAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_dev_dependencies_in_production()
    {
        $this->app->config->set('app.env', 'production');

        $this->runEnlightn();

        $this->assertFailed(DevDependencyAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_dev_dependencies_in_local()
    {
        $this->app->config->set('app.env', 'local');

        $this->runEnlightn();

        $this->assertPassed(DevDependencyAnalyzer::class);
    }
}
