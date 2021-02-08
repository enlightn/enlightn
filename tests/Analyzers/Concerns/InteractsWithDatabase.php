<?php


namespace Enlightn\Enlightn\Tests\Analyzers\Concerns;

trait InteractsWithDatabase
{
    protected function setupDatabase($app)
    {
        $app->config->set('database.connections.testmysql', [
            'driver' => 'mysql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'port' => env('DB_MYSQL_PORT'),
            'username' => env('DB_MYSQL_USERNAME'),
            'password' => env('DB_MYSQL_PASSWORD'),
        ]);
        $app->config->set('database.connections.testpgsql', [
            'driver' => 'pgsql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'port' => env('DB_PGSQL_PORT'),
            'username' => env('DB_PGSQL_USERNAME'),
            'password' => env('DB_PGSQL_PASSWORD'),
        ]);
    }

    protected function loadDefaultConnection($connectionName)
    {
        $this->app->config->set('database.default', $connectionName);
    }

    public function databaseDataProvider()
    {
        return [
            ['testmysql'],
            ['testpgsql'],
        ];
    }

    protected function loadMigrationFromStub($stub)
    {
        $this->loadMigrationsFrom(
            __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR
            .'Stubs/Database/'.$stub
        );
    }
}
