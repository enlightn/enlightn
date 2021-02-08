<?php

namespace Enlightn\Enlightn\Tests;

use Enlightn\Enlightn\Enlightn;
use Enlightn\Enlightn\Inspection\Inspector;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Enlightn\Enlightn\EnlightnServiceProvider;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class TestCase extends OrchestraTestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->app->make(Inspector::class)->flush();

        parent::tearDown();

        Enlightn::flush();
    }

    protected function getPackageProviders($app)
    {
        return [
            EnlightnServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->config->set('enlightn.base_path', __DIR__.DIRECTORY_SEPARATOR.'Stubs');

        $app->config->set('enlightn.analyzer_paths', [
            'Enlightn\\Enlightn\\Analyzers' => __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'src/Analyzers',
        ]);
    }
}
