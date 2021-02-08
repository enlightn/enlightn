<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidFunctionCallAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\InvalidFunctionCallStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;

class InvalidFunctionCallAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidFunctionCallAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_invalid_function_calls()
    {
        $this->setBasePathFrom(InvalidFunctionCallStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidFunctionCallAnalyzer::class, $this->getClassStubPath(InvalidFunctionCallStub::class), 9);
        $this->assertFailedAt(InvalidFunctionCallAnalyzer::class, $this->getClassStubPath(InvalidFunctionCallStub::class), 11);
        $this->assertHasErrors(InvalidFunctionCallAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_with_no_invalid_function_calls()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidFunctionCallAnalyzer::class);
    }
}
