<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Illuminate\Contracts\Config\Repository as ConfigRepository;

class HashingStrengthAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'A secure hashing strength is configured.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_CRITICAL;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 1;

    /**
     * The error message describing the analyzer insights.
     *
     * @var string|null
     */
    public $errorMessage = null;

    /**
     * Determine whether the analyzer should be run in CI mode.
     *
     * @var bool
     */
    public static $runInCI = false;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return $this->errorMessage
                ?? ("Your password hashing strength is set below the recommended threshold. "
                ."This weakens the app's security against brute-force attacks.");
    }

    /**
     * Execute the analyzer.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @return void
     */
    public function handle(ConfigRepository $config)
    {
        $driver = $config->get('hashing.driver');

        if ($driver === 'bcrypt') {
            if ($config->get('hashing.bcrypt.rounds') < 12) {
                $this->markFailed();
            }
        } elseif ($driver === 'argon' || $driver === 'argon2id') {
            if ($config->get('hashing.argon.memory') < 65536 || $config->get('hashing.argon.time') < 2) {
                $this->markFailed();
            }
        } else {
            $this->markSkipped();
        }
    }
}
