<?php

namespace Enlightn\Enlightn\Tests;

use Enlightn\Enlightn\Analyzers\Reliability\CachePrefixAnalyzer;
use Enlightn\Enlightn\Analyzers\Security\AppDebugAnalyzer;
use Symfony\Component\Console\Helper\TableStyle;

class EnlightnCommandTest extends TestCase
{
    /**
     * @test
     */
    public function command_exits_with_success_status_code_on_passing()
    {
        $this->app->config->set('enlightn.analyzers', AppDebugAnalyzer::class);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', false);

        $this->artisan('enlightn')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function command_exits_with_failure_status_code_on_failing()
    {
        $this->app->config->set('enlightn.analyzers', AppDebugAnalyzer::class);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);

        $this->artisan('enlightn')->assertExitCode(1);
    }

    /**
     * @test
     */
    public function command_exits_with_success_status_code_if_all_pass()
    {
        $this->app->config->set('enlightn.analyzers', [AppDebugAnalyzer::class, CachePrefixAnalyzer::class]);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', false);
        $this->app->config->set('cache.prefix', 'enlightn_cache');

        $this->artisan('enlightn')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function command_exits_with_failure_status_code_if_any_one_fails()
    {
        $this->app->config->set('enlightn.analyzers', [AppDebugAnalyzer::class, CachePrefixAnalyzer::class]);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('cache.prefix', 'enlightn_cache');

        $this->artisan('enlightn')->assertExitCode(1);
    }

    /**
     * @test
     */
    public function computes_percentage_properly()
    {
        $this->app->config->set('enlightn.analyzers', [AppDebugAnalyzer::class, CachePrefixAnalyzer::class]);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('cache.prefix', 'enlightn_cache');

        $rightAlign = (new TableStyle())->setPadType(STR_PAD_LEFT);

        $this->artisan('enlightn')
            ->assertExitCode(1)
            ->expectsOutput("Report Card")
            ->expectsTable(
                ['Status', 'Reliability', 'Security', 'Total'],
                [
                    ['Passed', '1 (100%)', '0   (0%)', '1  (50%)'],
                    ['Failed', '0   (0%)', '1 (100%)', '1  (50%)'],
                    ['Not Applicable', '0   (0%)', '0   (0%)', '0   (0%)'],
                    ['Error', '0   (0%)', '0   (0%)', '0   (0%)'],
                ],
                'default',
                ['default', $rightAlign, $rightAlign, $rightAlign]
            );
    }

    /**
     * @test
     */
    public function command_exits_with_success_status_code_if_not_reportable()
    {
        $this->app->config->set('enlightn.analyzers', AppDebugAnalyzer::class);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('enlightn.dont_report', [AppDebugAnalyzer::class]);

        $this->artisan('enlightn')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function command_exits_with_failure_status_code_if_any_one_reportable_fails()
    {
        $this->app->config->set('enlightn.analyzers', [AppDebugAnalyzer::class, CachePrefixAnalyzer::class]);
        $this->app->config->set('app.env', 'production');
        $this->app->config->set('app.debug', true);
        $this->app->config->set('enlightn.dont_report', [AppDebugAnalyzer::class]);

        $this->artisan('enlightn')->assertExitCode(1);
    }
}
