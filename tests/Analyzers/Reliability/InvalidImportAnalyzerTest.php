<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidImportAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidImportStub;

class InvalidImportAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidImportAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_missing_return_statements()
    {
        $this->setBasePathFrom(InvalidImportStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidImportAnalyzer::class, $this->getClassStubPath(InvalidImportStub::class), 5);
        $this->assertHasErrors(InvalidImportAnalyzer::class, 1);
    }

    /**
     * @test
     */
    public function passes_with_no_return_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidImportAnalyzer::class);
    }
}
