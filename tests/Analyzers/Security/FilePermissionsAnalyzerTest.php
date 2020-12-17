<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\FilePermissionsAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithMiddleware;

class FilePermissionsAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithMiddleware;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(FilePermissionsAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_for_max_permissions()
    {
        $this->app->config->set('enlightn.allowed_permissions', [
            __DIR__ => '777',
        ]);

        $this->runEnlightn();

        $this->assertPassed(FilePermissionsAnalyzer::class);
    }

    /**
     * @test
     */
    public function fails_for_min_permissions()
    {
        $this->app->config->set('enlightn.allowed_permissions', [
            __DIR__ => '000',
        ]);

        $this->runEnlightn();

        $this->assertFailed(FilePermissionsAnalyzer::class);
    }
}
