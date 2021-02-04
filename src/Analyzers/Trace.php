<?php

namespace Enlightn\Enlightn\Analyzers;

class Trace
{
    /**
     * @var int
     */
    public $lineNumber;

    /**
     * @var string|null
     */
    public $details;

    /**
     * @var string|null
     */
    public $path;

    public function __construct($path, $lineNumber, $details = null)
    {
        $this->path = $path;
        $this->lineNumber = $lineNumber;
        $this->details = $details;
    }
}
