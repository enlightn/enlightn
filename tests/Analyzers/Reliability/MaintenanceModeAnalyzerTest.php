<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\MaintenanceModeAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class MaintenanceModeAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(MaintenanceModeAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function confirms_is_not_down()
    {
        $this->runEnlightn();

        $this->assertPassed(MaintenanceModeAnalyzer::class);
    }
}
