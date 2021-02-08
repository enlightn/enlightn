<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        Product::query()->update($request->all());
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

    public function savedRequestDataTest(FormRequest $request)
    {
        $x = $request->all();
        Product::where('somestuff', 1)->update($x);
        (new Product)->forceFill($x)->save();
    }

    public function safeBuilderTest(FormRequest $request)
    {
        Product::where('someColumn', 1)->update($request->validated());
        Product::where('someColumn', 1)->where('anothercolumn', 2)->update($request->only(['somevalues']));
        Product::query()->upsert($request->only(['ok']), []);
    }

    public function safeRequestDataWhitelistTest(Request $request)
    {
        $x = $request->all();
        Product::where('somestuff', 1)->update(Arr::only($x, ['name', 'description']));
        Product::where('somestuff', 1)->update(array_filter($x, function ($key) {
            return in_array($key, ['name', 'description']);
        }));
    }
}

class Product extends Model
{
}
