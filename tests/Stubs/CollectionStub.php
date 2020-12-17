<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

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

class BananaModel extends Model
{
}
