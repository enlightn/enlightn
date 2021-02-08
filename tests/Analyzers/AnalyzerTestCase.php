<?php

namespace Enlightn\Enlightn\Tests\Analyzers;

use Enlightn\Enlightn\Enlightn;
use Enlightn\Enlightn\PHPStan;
use Enlightn\Enlightn\Tests\TestCase;
use Mockery as m;
use ReflectionClass;

class AnalyzerTestCase extends TestCase
{
    protected function setupEnvironmentFor($analyzerClass, $app, $instance = null)
    {
        $app->config->set('enlightn.analyzers', $analyzerClass);

        $app->config->set('enlightn.config_path', $this->getConfigStubPath());

        $app->config->set('enlightn.skip_env_specific', true);

        if (! $instance) {
            $app->singleton($analyzerClass);
        } else {
            $app->singleton($analyzerClass, function () use ($instance) {
                return $instance;
            });
        }

        $app->singleton(PHPStan::class, function ($app) {
            return new PHPStan(
                $app->make('files'),
                __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'
            );
        });
    }

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \ReflectionException
     * @throws \Throwable
     */
    protected function runEnlightn()
    {
        Enlightn::$rethrowExceptions = true;
        Enlightn::register();
        Enlightn::run($this->app);
    }

    protected function assertPassed($analyzerClass)
    {
        $this->assertNotSkipped($analyzerClass);
        $this->assertTrue($this->app->make($analyzerClass)->passed());
    }

    protected function assertFailed($analyzerClass)
    {
        $this->assertNotSkipped($analyzerClass);
        $this->assertFalse(($analyzer = $this->app->make($analyzerClass))->passed());
        $this->assertIsString($analyzer->getErrorMessage());
    }

    protected function assertHasErrors($analyzerClass, $numErrors)
    {
        $analyzer = $this->app->make($analyzerClass);
        $this->assertCount($numErrors, $analyzer->traces);
    }

    protected function assertFailedAt($analyzerClass, $path, $lineNumber)
    {
        $analyzer = $this->app->make($analyzerClass);
        $this->assertFailed($analyzerClass);
        $this->assertTrue(collect($analyzer->traces)->contains(function ($trace) use ($path, $lineNumber) {
            return $trace->lineNumber == $lineNumber && ($trace->path == $path || $trace->path == realpath($path));
        }));
    }

    protected function assertNotFailedAt($analyzerClass, $path, $lineNumber)
    {
        $analyzer = $this->app->make($analyzerClass);
        $this->assertFalse(collect($analyzer->traces)->contains(function ($trace) use ($path, $lineNumber) {
            return $trace->lineNumber == $lineNumber && ($trace->path == $path || $trace->path == realpath($path));
        }));
    }

    protected function assertSkipped($analyzerClass)
    {
        $this->assertTrue($this->app->make($analyzerClass)->skipped());
    }

    protected function assertNotSkipped($analyzerClass)
    {
        $this->assertFalse($this->app->make($analyzerClass)->skipped());
    }

    protected function getConfigStubPath($key = null)
    {
        return $this->getBaseStubPath().DIRECTORY_SEPARATOR.'config'
                .($key ? (DIRECTORY_SEPARATOR."{$key}.php") : '');
    }

    protected function getViewStubPath()
    {
        return $this->getBaseStubPath().DIRECTORY_SEPARATOR.'views';
    }

    protected function getAssetsStubPath()
    {
        return $this->getBaseStubPath().DIRECTORY_SEPARATOR.'assets';
    }

    protected function getBaseStubPath()
    {
        return dirname((new ReflectionClass(TestCase::class))->getFileName())
            .DIRECTORY_SEPARATOR.'Stubs';
    }

    protected function assertErrorMessageContains($analyzerClass, $search)
    {
        $analyzer = $this->app->make($analyzerClass);
        $this->assertStringContainsString($search, $analyzer->getErrorMessage());
    }

    protected function assertErrorMessageDoesNotContain($analyzerClass, $search)
    {
        $analyzer = $this->app->make($analyzerClass);
        $this->assertStringNotContainsString($search, $analyzer->getErrorMessage());
    }

    protected function getClassStubPath($stubClass)
    {
        return (new ReflectionClass($stubClass))->getFileName();
    }

    protected function setBasePathFrom($stubClass)
    {
        $this->app->config->set(
            'enlightn.base_path',
            $this->getClassStubPath($stubClass)
        );
    }

    protected function loadConfigFromStub($key, $app)
    {
        $app->config->set($key, array_merge(
            require $this->getConfigStubPath($key),
            $app->config->get($key, [])
        ));
    }

    protected function getMockedAnalyzer($analyzerClass)
    {
        $analyzer = m::mock($analyzerClass)->makePartial();

        $analyzer->shouldReceive('skip')->andReturn(false);

        return $analyzer;
    }
}
