<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidMethodCallAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\InvalidMethodCallStub;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;

class InvalidMethodCallAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidMethodCallAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function detects_invalid_method_calls()
    {
        $this->setBasePathFrom(InvalidMethodCallStub::class);

        $this->runEnlightn();

        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 9);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 10);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 30);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 31);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 33);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 40);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 41);
        $this->assertFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 42);
        $this->assertHasErrors(InvalidMethodCallAnalyzer::class, 8);
    }

    /**
     * @test
     */
    public function ignores_errors()
    {
        $this->setBasePathFrom(InvalidMethodCallStub::class);
        $this->app->config->set('enlightn.ignore_errors', [InvalidMethodCallAnalyzer::class => [
            [
                'path' => $this->getClassStubPath(InvalidMethodCallStub::class),
                'details' => 'Call to an undefined method Enlightn\Enlightn\Tests\Stubs\InvalidMethodCallStub::protectedMethodFromChild().',
            ],
            [
                'path' => $this->getClassStubPath(InvalidMethodCallStub::class),
                'details' => '*undefined method Enlightn\Enlightn\Tests\Stubs\InvalidMethodCallStub::lorem*',
            ]
        ]]);

        $this->runEnlightn();

        $this->assertNotFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 9);
        $this->assertNotFailedAt(InvalidMethodCallAnalyzer::class, $this->getClassStubPath(InvalidMethodCallStub::class), 10);
        $this->assertHasErrors(InvalidMethodCallAnalyzer::class, 6);
    }

    /**
     * @test
     */
    public function passes_with_no_invalid_method_calls()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidMethodCallAnalyzer::class);
    }
}
