<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\MissingModelRelationAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\MissingRelationStub;

class MissingModelRelationAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(MissingModelRelationAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_missing_relations()
    {
        $this->setBasePathFrom(MissingRelationStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(MissingModelRelationAnalyzer::class, $this->getClassStubPath(MissingRelationStub::class), 19);
        $this->assertFailedAt(MissingModelRelationAnalyzer::class, $this->getClassStubPath(MissingRelationStub::class), 20);
        $this->assertHasErrors(MissingModelRelationAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_with_no_relations()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(MissingModelRelationAnalyzer::class);
    }
}
