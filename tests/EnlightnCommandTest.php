<?php

namespace Enlightn\Enlightn\Tests;

use Enlightn\Enlightn\Analyzers\Reliability\CachePrefixAnalyzer;
use Enlightn\Enlightn\Analyzers\Security\AppDebugAnalyzer;

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
}
