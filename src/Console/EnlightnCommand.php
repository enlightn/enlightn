<?php

namespace Enlightn\Enlightn\Console;

use Enlightn\Enlightn\Console\Formatters\ReportFormatter;
use Enlightn\Enlightn\Enlightn;
use Illuminate\Console\Command;

class EnlightnCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enlightn
                            {analyzer?* : The analyzer class that you wish to run}
                            {--details : Show details of each failed check}
                            {--ci : Run Enlightn in CI Mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enlightn your application!';

    /**
     * The final result of the analysis.
     *
     * @var array
     */
    public $result = [];

    /**
     * The number of analyzers to run.
     *
     * @var int
     */
    protected $totalAnalyzers;

    /**
     * The number of analyzers that have completed their analysis.
     *
     * @var int
     */
    protected $countAnalyzers;

    /**
     * The analyzer classes to run. All classes will run if empty.
     *
     * @var array
     */
    protected $analyzerClasses;

    /**
     * @var \Enlightn\Enlightn\Console\Formatters\Formatter
     */
    protected $formatter;

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \ReflectionException|\Illuminate\Contracts\Container\BindingResolutionException|\Throwable
     */
    public function handle()
    {
        $this->analyzerClasses = $this->argument('analyzer');

        $this->formatter = new ReportFormatter;

        $this->formatter->beforeAnalysis($this);

        if ($this->option('ci')) {
            Enlightn::filterAnalyzersForCI();
        }

        Enlightn::register($this->analyzerClasses);

        $this->totalAnalyzers = Enlightn::totalAnalyzers();
        $this->countAnalyzers = 1;
        $this->initializeResult();

        Enlightn::using([$this, 'printAnalyzerOutput']);
        Enlightn::run($this->laravel);

        $this->formatter->afterAnalysis($this, empty($this->analyzerClasses));

        // Exit with a non-zero exit code if there were failed checks to throw an error on CI environments
        return collect($this->result)->sum(function ($category) {
            return $category['reported'];
        }) == 0 ? 0 : 1;
    }

    /**
     * @param array $info
     *
     * @return void
     */
    public function printAnalyzerOutput(array $info)
    {
        $this->formatter->parseAnalyzerResult(
            $this,
            $info,
            $this->countAnalyzers,
            $this->totalAnalyzers,
            empty($this->analyzerClasses)
        );

        $this->updateResult($info);

        $this->countAnalyzers++;
    }

    /**
     * Initialize the result.
     *
     * @return $this
     */
    protected function initializeResult()
    {
        $this->result = [];

        foreach (array_merge(Enlightn::$categories, ['Total']) as $category) {
            $this->result[$category] = [
                'passed' => 0,
                'failed' => 0,
                'skipped' => 0,
                'error' => 0,
                'reported' => 0,
            ];
        }

        return $this;
    }

    /**
     * Update the result based on the analysis.
     *
     * @param array $info
     * @return string
     */
    protected function updateResult(array $info)
    {
        $this->result[$info['category']][$info['status']]++;
        $this->result['Total'][$info['status']]++;
        if ($info['status'] === 'failed' && ($info['reportable'] ?? true)) {
            $this->result[$info['category']]['reported']++;
            $this->result['Total']['reported']++;
        }
    }
}
