<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\MassAssignmentAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\MassAssignmentStub;

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

        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 26);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 33);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 38);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 43);

        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 14);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 21);

        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 48);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 49);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 50);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 56);
        $this->assertFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 57);

        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 62);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 63);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 64);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 70);
        $this->assertNotFailedAt(MassAssignmentAnalyzer::class, $this->getClassStubPath(MassAssignmentStub::class), 71);

        $this->assertHasErrors(MassAssignmentAnalyzer::class, 9);
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
