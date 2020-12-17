<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\UpToDateDependencyAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;

class UpToDateDependencyAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(UpToDateDependencyAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_with_up_to_date_dependencies()
    {
        $this->runEnlightn();

        $this->assertPassed(UpToDateDependencyAnalyzer::class);
    }
}
