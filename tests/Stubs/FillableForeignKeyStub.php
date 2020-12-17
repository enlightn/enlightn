<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

class FillableForeignKeyStub extends Model
{
    // Declares potential foreign key: user_id.
    protected $fillable = ['profile_pic', 'type', 'safe', 'user_id'];
}

class SafeModel extends Model
{
    // Declares safe fillable attributes.
    protected $fillable = ['profile_pic', 'type', 'safe', 'anothersafe'];
}

class NoDeclarationModel extends Model
{
    // Does not declare fillable.
}
