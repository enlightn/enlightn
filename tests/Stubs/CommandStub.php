<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Console\Command;

class CommandStub extends Command
{
    // Non lazy-loaded command with constructor injection.
    public function __construct(DummyStub $stub)
    {
    }
}

class LazyCommand extends Command
{
    public static $defaultName = 'command:name';

    // Lazy-loaded command with constructor injection.
    public function __construct(DummyStub $stub)
    {
    }
}

class SafeCommand extends Command
{
    // No constructor injection.
}
