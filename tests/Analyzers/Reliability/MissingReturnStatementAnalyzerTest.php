<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\MissingReturnStatementAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\MissingReturnStub;

class MissingReturnStatementAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(MissingReturnStatementAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_missing_return_statements()
    {
        $this->setBasePathFrom(MissingReturnStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(MissingReturnStatementAnalyzer::class, $this->getClassStubPath(MissingReturnStub::class), 7);
        $this->assertFailedAt(MissingReturnStatementAnalyzer::class, $this->getClassStubPath(MissingReturnStub::class), 13);
        $this->assertHasErrors(MissingReturnStatementAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_with_no_return_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(MissingReturnStatementAnalyzer::class);
    }
}
