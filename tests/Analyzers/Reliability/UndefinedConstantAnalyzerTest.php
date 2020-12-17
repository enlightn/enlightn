<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UndefinedConstantAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UndefinedConstantStub;

class UndefinedConstantAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(UndefinedConstantAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_missing_return_statements()
    {
        $this->setBasePathFrom(UndefinedConstantStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UndefinedConstantAnalyzer::class, $this->getClassStubPath(UndefinedConstantStub::class), 9);
        $this->assertHasErrors(UndefinedConstantAnalyzer::class, 1);
    }

    /**
     * @test
     */
    public function passes_with_no_return_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UndefinedConstantAnalyzer::class);
    }
}
