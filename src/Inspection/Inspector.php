<?php

namespace Enlightn\Enlightn\Inspection;

use Illuminate\Support\Arr;
use Illuminate\Filesystem\Filesystem;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;

class Inspector
{
    protected $files;

    public $nodes = [];

    public $passed = true;

    /**
     * @var array
     */
    protected $errorLineNumbers = [];

    public function __construct()
    {
        $this->files = new Filesystem;
    }

    /**
     * @param  array  $filePaths
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function start(array $filePaths)
    {
        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);

        collect($filePaths)->each(function ($path) use ($parser) {
            if (! isset($this->nodes[$path])) {
                $this->nodes[$path] = $parser->parse($this->files->get($path));
            }
        });

        return $this;
    }

    /**
     * @param QueryBuilder $builder
     * @return array
     */
    public function inspect(QueryBuilder $builder)
    {
        $this->errorLineNumbers = [];
        $this->passed = true;

        foreach ($this->nodes as $path => $nodes) {
            if (! empty($lineNumbers = $builder->getErrors($nodes))) {
                $this->errorLineNumbers[$path] = $lineNumbers;
            }

            $this->passed = $this->passed && $builder->passed();
        }

        return $this->errorLineNumbers;
    }

    /**
     * Determine whether the inspector passed the last inspection.
     *
     * @return bool
     */
    public function passed()
    {
        return $this->passed;
    }

    /**
     * @return array
     */
    public function getLastErrors()
    {
        return $this->errorLineNumbers;
    }

    public function flush()
    {
        $this->nodes = [];

        return $this;
    }
}
