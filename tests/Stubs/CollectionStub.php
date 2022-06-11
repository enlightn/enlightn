<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Enlightn\Enlightn\Tests\Stubs\Models\BananaModel;

class CollectionStub
{
    public function countTest()
    {
        return BananaModel::all()->count();
    }

    public function firstTest()
    {
        return BananaModel::all()->first();
    }
}
