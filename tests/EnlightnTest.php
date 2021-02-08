<?php

namespace Enlightn\Enlightn\Tests;

use Enlightn\Enlightn\Analyzers\Analyzer;
use Enlightn\Enlightn\Analyzers\Performance\CacheHeaderAnalyzer;
use Enlightn\Enlightn\Analyzers\Performance\SessionDriverAnalyzer;
use Enlightn\Enlightn\Analyzers\Reliability\DeadCodeAnalyzer;
use Enlightn\Enlightn\Analyzers\Security\AppDebugAnalyzer;
use Enlightn\Enlightn\Analyzers\Security\CSRFAnalyzer;
use Enlightn\Enlightn\Enlightn;
use Enlightn\Enlightn\Tests\Stubs\CustomCategoryStub;
use Mockery as m;

class EnlightnTest extends TestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->config->set('enlightn.analyzers', AppDebugAnalyzer::class);
    }

    /**
     * @test
     */
    public function registers_analyzer_classes()
    {
        Enlightn::register();

        $this->assertContains(AppDebugAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertNotContains(Analyzer::class, Enlightn::$analyzerClasses);
    }

    /**
     * @test
     */
    public function registers_analyzer_categories()
    {
        $this->app->config->set(
            'enlightn.analyzers',
            [
                CSRFAnalyzer::class,
                SessionDriverAnalyzer::class,
                DeadCodeAnalyzer::class,
                CustomCategoryStub::class,
            ]
        );

        Enlightn::register();

        $this->assertEquals(['Security', 'Performance', 'Reliability', 'Custom'], Enlightn::$categories);
    }

    /**
     * @test
     */
    public function excludes_analyzer_classes()
    {
        $this->app->config->set('enlightn.analyzers', '*');
        $this->app->config->set('enlightn.exclude_analyzers', [AppDebugAnalyzer::class]);

        Enlightn::register();

        $this->assertNotContains(AppDebugAnalyzer::class, Enlightn::$analyzerClasses);
    }

    /**
     * @test
     */
    public function filters_analyzer_classes_for_ci()
    {
        $this->app->config->set('enlightn.analyzers', '*');

        Enlightn::filterAnalyzersForCI();
        Enlightn::register();

        $this->assertNotContains(CacheHeaderAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertContains(DeadCodeAnalyzer::class, Enlightn::$analyzerClasses);
    }

    /**
     * @test
     */
    public function excludes_analyzer_classes_for_ci()
    {
        $this->app->config->set('enlightn.analyzers', '*');
        $this->app->config->set('enlightn.ci_mode_exclude_analyzers', [DeadCodeAnalyzer::class]);

        Enlightn::filterAnalyzersForCI();
        Enlightn::register();

        $this->assertNotContains(DeadCodeAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertNotContains(CacheHeaderAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertContains(AppDebugAnalyzer::class, Enlightn::$analyzerClasses);
    }

    /**
     * @test
     */
    public function allows_overriding_analyzer_classes_for_ci()
    {
        $this->app->config->set('enlightn.analyzers', '*');
        $this->app->config->set('enlightn.ci_mode_analyzers', [CacheHeaderAnalyzer::class, DeadCodeAnalyzer::class]);

        Enlightn::filterAnalyzersForCI();
        Enlightn::register();

        $this->assertContains(CacheHeaderAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertContains(DeadCodeAnalyzer::class, Enlightn::$analyzerClasses);
        $this->assertCount(2, Enlightn::$analyzerClasses);
    }

    /**
     * @test
     */
    public function runs_analyzer_classes()
    {
        Enlightn::register();

        $appDebugAnalyzer = m::mock(AppDebugAnalyzer::class);
        $appDebugAnalyzer->shouldReceive('run')->once();

        $this->app->singleton(AppDebugAnalyzer::class, function () use ($appDebugAnalyzer) {
            return $appDebugAnalyzer;
        });

        Enlightn::run($this->app);
    }

    /**
     * @test
     */
    public function parses_analyzer_classes_recursively()
    {
        $this->app->config->set('enlightn.analyzers', '*');

        $this->assertContains(AppDebugAnalyzer::class, Enlightn::getAnalyzerClasses());
    }
}
