<?php

namespace Enlightn\Enlightn\Inspection;

use Illuminate\Filesystem\Filesystem;
use PhpParser\Error;
use PhpParser\ErrorHandler\Collecting;
use PhpParser\ParserFactory;

class Inspector
{
    protected $files;

    public $nodes = [];

    public $errors = [];

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
     */
    public function start(array $filePaths)
    {
        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);

        collect($filePaths)->each(function ($path) use ($parser) {
            if (! isset($this->nodes[$path])) {
                $this->nodes[$path] = $parser->parse($this->files->get($path), $handler = new Collecting);

                if ($handler->hasErrors()) {
                    // Partial ASTs are ignored.
                    unset($this->nodes[$path]);

                    $this->errors[$path] = collect($handler->getErrors())->map(function (Error $error) {
                        return $error->getStartLine();
                    })->toArray();
                }
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

        $this->errors = [];

        return $this;
    }
}
