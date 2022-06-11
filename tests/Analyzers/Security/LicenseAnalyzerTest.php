<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\LicenseAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Enlightn\Enlightn\Tests\Analyzers\Concerns\InteractsWithComposer;

class LicenseAnalyzerTest extends AnalyzerTestCase
{
    use InteractsWithComposer;

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->replaceComposer($app);

        $this->setupEnvironmentFor(LicenseAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function confirms_enlightn_uses_dependencies_with_safe_licenses()
    {
        // set GPL 2 to be valid for Enlightn, since Larastan uses phpmyadmin/sql-parser
        $this->app->config->set('enlightn.license_whitelist', [...config('enlightn.license_whitelist'), 'GPL-2.0-OR-LATER']);

        $this->runEnlightn();

        $this->assertPassed(LicenseAnalyzer::class);
    }
}
