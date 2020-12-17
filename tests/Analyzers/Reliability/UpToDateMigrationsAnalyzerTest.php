<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UpToDateMigrationsAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Support\Facades\Artisan;

class UpToDateMigrationsAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(UpToDateMigrationsAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_with_no_remaining_migrations()
    {
        Artisan::shouldReceive('call');
        Artisan::shouldReceive('output')->andReturn('Nothing to migrate.');

        $this->runEnlightn();

        $this->assertPassed(UpToDateMigrationsAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_pending_migrations()
    {
        Artisan::shouldReceive('call');
        Artisan::shouldReceive('output')->andReturn('create some table');

        $this->runEnlightn();

        $this->assertFailed(UpToDateMigrationsAnalyzer::class);
    }
}
