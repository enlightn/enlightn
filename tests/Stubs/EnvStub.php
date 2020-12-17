<?php

namespace Enlightn\Enlightn\Tests\Stubs;

class EnvStub
{
    public function envCallTest()
    {
        env('This call fails while config is cached');
    }
}
