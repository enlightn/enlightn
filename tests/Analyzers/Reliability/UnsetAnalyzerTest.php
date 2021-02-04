<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\UnsetAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UnsetStub;

class UnsetAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(UnsetAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_invalid_unset_statements()
    {
        $this->setBasePathFrom(UnsetStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 9);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 10);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 13);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 16);
        $this->assertFailedAt(UnsetAnalyzer::class, $this->getClassStubPath(UnsetStub::class), 19);
        $this->assertHasErrors(UnsetAnalyzer::class, 6);
    }

    /**
     * @test
     */
    public function passes_with_no_unset_statements()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UnsetAnalyzer::class);
    }
}
