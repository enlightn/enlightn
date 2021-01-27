<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Enlightn\Enlightn\Analyzers\Analyzer;

class CustomCategoryStub extends Analyzer
{
    /**
     * The category of the analyzer.
     *
     * @var string|null
     */
    public $category = 'Custom';
}
