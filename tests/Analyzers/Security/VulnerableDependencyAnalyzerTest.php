<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\VulnerableDependencyAnalyzer;
use Enlightn\Enlightn\Composer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;
use Illuminate\Foundation\Application;
use Illuminate\Support\Str;

class VulnerableDependencyAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(VulnerableDependencyAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function confirms_enlightn_has_no_vulnerable_dependencies()
    {
        if (Str::startsWith(Application::VERSION, '7')) {
            // Since 7.x is no longer receiving security updates, we need to skip this version.
            $this->markTestSkipped();
        }

        $this->runEnlightn();

        $this->assertPassed(VulnerableDependencyAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_vulnerable_dependencies()
    {
        app(Composer::class)->setWorkingPath($this->getBaseStubPath());

        $this->runEnlightn();

        $this->assertFailed(VulnerableDependencyAnalyzer::class);
        $this->assertErrorMessageContains(VulnerableDependencyAnalyzer::class, 'laravel/framework');
        $this->assertErrorMessageContains(VulnerableDependencyAnalyzer::class, '8.22.0');
        $this->assertErrorMessageContains(VulnerableDependencyAnalyzer::class, 'Unexpected bindings in QueryBuilder');
    }
}
