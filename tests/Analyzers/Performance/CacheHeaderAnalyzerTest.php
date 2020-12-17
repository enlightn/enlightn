<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Performance;

use Enlightn\Enlightn\Analyzers\Performance\CacheHeaderAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Filesystem\Filesystem;

class CacheHeaderAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(CacheHeaderAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function skips_without_mix_manifest()
    {
        $this->runEnlightn();

        $this->assertSkipped(CacheHeaderAnalyzer::class);
    }

    /**
     * @test
     */
    public function detects_missing_cache_headers()
    {
        $this->app->config->set('app.env', 'production');

        (new Filesystem())->copy(
            $this->getBaseStubPath().DIRECTORY_SEPARATOR.'mix'.DIRECTORY_SEPARATOR.'mix-manifest-versioned.json',
            public_path('mix-manifest.json')
        );

        $this->app->make(CacheHeaderAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, []),
                new Response(200, []),
            ])]
        ));

        $this->runEnlightn();

        $this->assertFailed(CacheHeaderAnalyzer::class);
        $this->assertErrorMessageContains(CacheHeaderAnalyzer::class, 'app.js');
        $this->assertErrorMessageContains(CacheHeaderAnalyzer::class, 'app.css');

        (new Filesystem())->delete(public_path('mix-manifest.json'));
    }

    /**
     * @test
     */
    public function passes_with_cache_headers()
    {
        $this->app->config->set('app.env', 'production');

        (new Filesystem())->copy(
            $this->getBaseStubPath().DIRECTORY_SEPARATOR.'mix'.DIRECTORY_SEPARATOR.'mix-manifest-versioned.json',
            public_path('mix-manifest.json')
        );

        $this->app->make(CacheHeaderAnalyzer::class)->setClient(new Client(
            ['handler' => new MockHandler([
                new Response(200, ['Cache-Control' => 'max-age=86400']),
                new Response(200, ['Cache-Control' => 'max-age=86400']),
            ])]
        ));

        $this->runEnlightn();

        $this->assertPassed(CacheHeaderAnalyzer::class);

        (new Filesystem())->delete(public_path('mix-manifest.json'));
    }
}
