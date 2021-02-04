<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidOffsetAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\InvalidOffsetStub;

class InvalidOffsetAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidOffsetAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_invalid_offset()
    {
        $this->setBasePathFrom(InvalidOffsetStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 10);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 13);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 17);
        $this->assertFailedAt(InvalidOffsetAnalyzer::class, $this->getClassStubPath(InvalidOffsetStub::class), 25);
        $this->assertHasErrors(InvalidOffsetAnalyzer::class, 5);
    }

    /**
     * @test
     */
    public function passes_with_no_offset()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidOffsetAnalyzer::class);
    }
}
