<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Analyzers\Reliability\ComposerValidationAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;

class ComposerValidationAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(ComposerValidationAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function confirms_passes_for_enlightn()
    {
        $this->runEnlightn();

        $this->assertPassed(ComposerValidationAnalyzer::class);
    }
}
