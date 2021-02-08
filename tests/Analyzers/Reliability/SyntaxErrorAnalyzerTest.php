<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\SyntaxErrorAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;

class SyntaxErrorAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(SyntaxErrorAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_syntax_errors()
    {
        $this->app->config->set('enlightn.base_path', $this->getBaseStubPath());

        $this->runEnlightn();

        $errorPath = $this->getBaseStubPath().DIRECTORY_SEPARATOR.'SyntaxErrorStub.php';

        $this->assertFailedAt(SyntaxErrorAnalyzer::class, $errorPath, 5);
        $this->assertFailedAt(SyntaxErrorAnalyzer::class, $errorPath, 7);
        $this->assertHasErrors(SyntaxErrorAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_with_no_errors()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(SyntaxErrorAnalyzer::class);
    }
}
