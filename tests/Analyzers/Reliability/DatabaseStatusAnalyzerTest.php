<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Reliability;

use Enlightn\Enlightn\Analyzers\Reliability\DatabaseStatusAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Mockery as m;

class DatabaseStatusAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(DatabaseStatusAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function checks_db_access()
    {
        $this->app->config->set('database.default', 'mysql');

        $connection = m::mock(Connection::class);
        $connection->shouldReceive('getPdo');

        DB::shouldReceive('connection')->andReturn($connection);

        $this->runEnlightn();

        $this->assertPassed(DatabaseStatusAnalyzer::class);
    }
}
