<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\EnvFileAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class EnvFileAnalyzerTest extends AnalyzerTestCase
{
    protected $files;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(EnvFileAnalyzer::class, $app);

        $this->files = m::mock(Filesystem::class);

        $app->singleton(Filesystem::class, function () {
            return $this->files;
        });
    }

    /**
     * @test
     */
    public function passes_with_env_file()
    {
        $this->files->shouldReceive('exists')->with(base_path('.env'))->andReturn(true);

        $this->runEnlightn();

        $this->assertPassed(EnvFileAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_missing_env_file()
    {
        $this->files->shouldReceive('exists')->with(base_path('.env'))->andReturn(false);

        $this->runEnlightn();

        $this->assertFailed(EnvFileAnalyzer::class);
    }
}
