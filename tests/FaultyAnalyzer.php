<?php

namespace Enlightn\Enlightn\Tests;

use Enlightn\Enlightn\Analyzers\Performance\PerformanceAnalyzer;
use RuntimeException;

class FaultyAnalyzer extends PerformanceAnalyzer
{
    /**
     * The title describing the analyzer.
     *
     * @var string|null
     */
    public $title = 'For testing purposes.';

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
    public $timeToFix = 10;

    /**
     * Execute the analyzer.
     *
     * @return void
     */
    public function handle()
    {
        throw new RuntimeException('Test exception thrown by faulty analyzer.');
    }
}
