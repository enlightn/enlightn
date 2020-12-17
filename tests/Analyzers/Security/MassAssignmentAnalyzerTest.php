<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\MassAssignmentAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\MassAssignmentStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;

class MassAssignmentAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(MassAssignmentAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_mass_assignment_vulnerabilities()
    {
        $this->setBasePathFrom(MassAssignmentStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 25);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 32);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 37);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 42);

        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 13);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 20);

        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 47);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 48);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 49);

        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 54);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 55);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 56);

        $this->assertHasErrors(MassAssignmentAnalyzer::class, 7);
    }

    /**
     * @test
     */
    public function passes_with_no_injection_call()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(MassAssignmentAnalyzer::class);
    }
}
