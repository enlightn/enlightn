<?php

namespace Enlightn\Enlightn\Analyzers\Reliability;

use Illuminate\Filesystem\Filesystem;

class CustomErrorPageAnalyzer extends ReliabilityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = "Your application defines custom error page views.";

    /**
     * The severity of the analyzer.
     *
     * @var string|null
     */
    public $severity = self::SEVERITY_MINOR;

    /**
     * The time to fix in minutes.
     *
     * @var int|null
     */
    public $timeToFix = 60;

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
        return "Your application does not customize its error pages. This may hamper user experience and also exposes "
            ."your application to fingerprinting, which means potential attackers can identify Laravel as your framework.";
    }

    /**
     * Execute the analyzer.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function handle(Filesystem $files)
    {
        $hasCustomErrorPages = collect(config('view.paths'))->contains(function ($viewPath, $_) use ($files) {
            return $files->exists($viewPath.DIRECTORY_SEPARATOR.'errors'.DIRECTORY_SEPARATOR.'404.blade.php');
        });

        if (! $hasCustomErrorPages) {
            $this->markFailed();
        }
    }
}
