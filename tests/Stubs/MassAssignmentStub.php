<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class MassAssignmentStub
{
    public function safeCreateTest(Request $request)
    {
        Product::forceCreate($request->only(['somekey']));
    }

    public function safeFillTest(FormRequest $request)
    {
        $banana = new Product();

        $banana->forceFill($request->only(['somekey']));
    }

    public function forceCreateTest(Request $request)
    {
        Product::forceCreate($request->all());
    }

    public function forceFillTest(FormRequest $request)
    {
        $banana = new Product();

        $banana->forceFill($request->all());
    }

    public function builderMassUpdateTest(Request $request)
    {
        Product::update($request->all());
    }

    public function firstOrCreateTest(Request $request)
    {
        Product::firstOrCreate($request->all());
    }

    public function builderTest(Request $request)
    {
        Product::where('someColumn', 1)->update($request->all());
        Product::where('someColumn', 1)->where('anothercolumn', 2)->update($request->all());
        Product::query()->upsert($request->all(), []);
    }

    public function safeBuilderTest(Request $request)
    {
        Product::where('someColumn', 1)->update($request->validated());
        Product::where('someColumn', 1)->where('anothercolumn', 2)->update($request->only(['somevalues']));
        Product::query()->upsert($request->only(['ok']), []);
    }
}

class Product extends Model
{
}
