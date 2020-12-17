<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\InvalidMethodOverrideAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Stubs\DummyStub;

class InvalidMethodOverrideAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(InvalidMethodOverrideAnalyzer::class, $app);

        if (PHP_VERSION_ID >= 80000) {
            $this->markTestSkipped();
        }
    }

    /**
     * @test
     */
    public function detects_invalid_method_overrides()
    {
        $this->app->config->set(
            'enlightn.base_path',
            $path =__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.
                'Stubs/InvalidMethodOverrideStub.php'
        );

        $this->runEnlightn();

        $this->assertFailedAt(InvalidMethodOverrideAnalyzer::class, $path, 14);
        $this->assertHasErrors(InvalidMethodOverrideAnalyzer::class, 1);
    }

    /**
     * @test
     */
    public function passes_with_no_invalid_overrides()
    {
        $this->setBasePathFrom(DummyStub::class);

        $this->runEnlightn();

        $this->assertPassed(InvalidMethodOverrideAnalyzer::class);
    }
}
