<?php

namespace Enlightn\Enlightn\Tests\Analyzers\Security;

use Enlightn\Enlightn\Analyzers\Security\HashingStrengthAnalyzer;
use Enlightn\Enlightn\Tests\Analyzers\AnalyzerTestCase;

class HashingStrengthAnalyzerTest extends AnalyzerTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $this->setupEnvironmentFor(HashingStrengthAnalyzer::class, $app);
    }

    /**
     * @test
     */
    public function passes_for_secure_bcrypt()
    {
        $this->app->config->set('hashing.driver', 'bcrypt');
        $this->app->config->set('hashing.bcrypt.rounds', 12);

        $this->runEnlightn();

        $this->assertPassed(HashingStrengthAnalyzer::class);
    }

    /**
     * @test
     */
    public function fails_for_insecure_bcrypt()
    {
        $this->app->config->set('hashing.driver', 'bcrypt');
        $this->app->config->set('hashing.bcrypt.rounds', 10);

        $this->runEnlightn();

        $this->assertFailed(HashingStrengthAnalyzer::class);
    }

    /**
     * @test
     */
    public function passes_for_secure_argon()
    {
        $this->app->config->set('hashing.driver', 'argon');
        $this->app->config->set('hashing.argon.memory', 65536);
        $this->app->config->set('hashing.argon.threads', 1);
        $this->app->config->set('hashing.argon.time', 4);

        $this->runEnlightn();

        $this->assertPassed(HashingStrengthAnalyzer::class);
    }

    /**
     * @test
     */
    public function fails_for_insecure_argon()
    {
        $this->app->config->set('hashing.driver', 'argon');
        $this->app->config->set('hashing.argon.memory', 1024);

        $this->runEnlightn();

        $this->assertFailed(HashingStrengthAnalyzer::class);
    }
}
