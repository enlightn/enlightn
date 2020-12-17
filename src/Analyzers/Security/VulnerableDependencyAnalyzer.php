<?php

namespace Enlightn\Enlightn\Analyzers\Security;

use Enlightn\Enlightn\Composer;
use Illuminate\Contracts\Foundation\Application;
use SensioLabs\Security\SecurityChecker;
use Throwable;

class VulnerableDependencyAnalyzer extends SecurityAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'Your application does not rely on backend dependencies with known security issues.';

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
    public $timeToFix = 60;

    /**
     * The result of the vulnerability scan.
     *
     * @var \SensioLabs\Security\Result
     */
    public $result;

    /**
     * Get the error message describing the analyzer insights.
     *
     * @return string
     */
    public function errorMessage()
    {
        return "Your application has a total of {$this->result->count()} known vulnerabilities in the application "
            ."dependencies. This can be very dangerous and you must resolve this by either applying patch updates or "
            ."removing the vulnerable dependencies. The packages which have these vulnerabilities include: "
            .PHP_EOL.$this->listVulnerablePackages();
    }

    /**
     * Execute the analyzer.
     *
     * @param \Enlightn\Enlightn\Composer $composer
     * @return void
     */
    public function handle(Composer $composer)
    {
        $this->result = (new SecurityChecker())->check(
            $composer->getLockFile(),
            'json'
        );

        if ($this->result->count() > 0) {
            $this->markFailed();
        }
    }

    /**
     * List the vulnerable packages.
     *
     * @return string
     */
    public function listVulnerablePackages()
    {
        try {
            return collect(json_decode($this->result->__toString(), true))
                ->map(function ($vulnerability, $package) {
                    return $package.' ('.$vulnerability['version'].'): '.
                        collect(data_get($vulnerability, 'advisories.*.title'))
                        ->join(', ', ' and ');
                })->values()->implode(PHP_EOL);
        } catch (Throwable $e) {
            return $this->result->__toString();
        }
    }
}
