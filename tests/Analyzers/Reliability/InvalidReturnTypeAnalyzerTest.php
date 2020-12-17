<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidReturnTypeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidReturnTypeStub;

class InvalidReturnTypeAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidReturnTypeAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_invalid_offset()
    {
        $this->setBasePathFrom(InvalidReturnTypeStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 14);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 28);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 32);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 44);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 58);
        $this->assertFailedAt(InvalidReturnTypeAnalyzer::class, $this->getClassStubPath(InvalidReturnTypeStub::class), 62);
        $this->assertHasErrors(InvalidReturnTypeAnalyzer::class, 6);
    }

    /**
     * @test
     */
    public function passes_with_no_offset()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidReturnTypeAnalyzer::class);
    }
}
