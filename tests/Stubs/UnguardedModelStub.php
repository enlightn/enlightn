<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class UnguardedModelStub
{
    public function unguardAllModelsTest()
    {
        Model::unguard();
    }

    public function unguardSpecificModelTest()
    {
        Banana::unguard();
    }
}

class Banana extends Model
{
    //
}
