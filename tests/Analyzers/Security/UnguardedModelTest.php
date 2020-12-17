<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\UnguardedModelsAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;
use Enlightn\Enlightn\Tests\Stubs\UnguardedModelStub;

class UnguardedModelTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(UnguardedModelsAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_unguarded_models()
    {
        $this->setBasePathFrom(UnguardedModelStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(UnguardedModelsAnalyzer::class, $this->getClassStubPath(UnguardedModelStub::class), 11);
        $this->assertFailedAt(UnguardedModelsAnalyzer::class, $this->getClassStubPath(UnguardedModelStub::class), 16);
        $this->assertHasErrors(UnguardedModelsAnalyzer::class, 2);
    }

    /**
     * @test
     */
    public function passes_for_no_unguarded_models()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(UnguardedModelsAnalyzer::class);
    }
}
