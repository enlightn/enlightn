<?php

namespace Enlightn\Enlightn\Analyzers\Concerns;

use Enlightn\Enlightn\PHPStan;

trait ParsesPHPStanAnalysis
{
    /**
     * Parse the analysis and add traces for the errors.
     *
     * @param \Enlightn\Enlightn\PHPStan $phpStan
     * @param string|array $search
     */
    protected function parsePHPStanAnalysis(PHPStan $phpStan, $search)
    {
        collect($phpStan->parseAnalysis($search))->each(function ($lineNumbers, $path) {
            $this->addTraces($path, $lineNumbers);
        });
    }

    /**
     * Parse the analysis and add traces for the errors.
     *
     * @param \Enlightn\Enlightn\PHPStan $phpStan
     * @param string|array $pattern
     */
    protected function matchPHPStanAnalysis(PHPStan $phpStan, $pattern)
    {
        collect($phpStan->match($pattern))->each(function ($lineNumbers, $path) {
            $this->addTraces($path, $lineNumbers);
        });
    }
}
