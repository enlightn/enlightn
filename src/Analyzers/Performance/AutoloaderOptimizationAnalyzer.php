<?php

namespace Enlightn\Enlightn\Analyzers\Performance;

use Illuminate\Database\Query\Builder;

class AutoloaderOptimizationAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application has the Composer autoloader optimization configured in production.';

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MAJOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 5;

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
        return "Your Composer autoloader is not optimized while your application is in a non-local environment. "
            ."You should optimize the autoloader for improved performance.";
    }

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        /** @var \Composer\Autoload\ClassLoader $loader */
        $loader = require base_path('vendor/autoload.php');

        if (! $loader->isClassMapAuthoritative() && ! isset($loader->getClassMap()[Builder::class])) {
            // We assume here that if composer autoloader isn't optimized using the --classmap-authoritative flag
            // and does not have the classmap loaded for the Builder class, then it is not optimized because
            // PSR-4 rules should be converted into classmap rules with the -o flag.
            $this->markFailed();
        }
    }

    /**
     * Determine whether to skip the analyzer.
     *
     * @return bool
     */
    public function skip()
    {
        // Skip the analyzer if it's a local env.
        return config('app.env') === 'local';
    }
}
