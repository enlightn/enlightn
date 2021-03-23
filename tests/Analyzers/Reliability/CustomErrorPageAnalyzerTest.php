<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\CustomErrorPageAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class CustomErrorPageAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CustomErrorPageAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_no_custom_error_pages()
    {
        $this->runEnlightn();

        $this->assertFailed(CustomErrorPageAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_custom_error_pages()
    {
        $this->app->config->set('view.paths', [$this->getViewStubPath()]);

        $this->runEnlightn();

        $this->assertPassed(CustomErrorPageAnalyzer::class);
    }
}
