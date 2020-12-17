<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\DirectoryWritePermissionsAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Filesystem\Filesystem;
use Mockery as m;

class DirectoryWritePermissionsAnalyzerTest extends AnalyzerTestCase
{
    protected $files;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(DirectoryWritePermissionsAnalyzer::class, $app);

        $this->files = m::mock(Filesystem::class);

        $app->singleton(Filesystem::class, function() {
            return $this->files;
        });
    }

    /**
     * @test
     */
    public function passes_with_writable_directories()
    {
        $this->files->shouldReceive('isWritable')->andReturn(true);

        $this->runEnlightn();

        $this->assertPassed(DirectoryWritePermissionsAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_unwritable_directories()
    {
        $this->files->shouldReceive('isWritable')->andReturn(false);

        $this->runEnlightn();

        $this->assertFailed(DirectoryWritePermissionsAnalyzer::class);
    }
}
