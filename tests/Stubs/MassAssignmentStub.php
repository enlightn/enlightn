<?php

namespace Enlightn\Enlightn\Tests\Stubs;

use Enlightn\Enlightn\Tests\Stubs\Models\BananaModel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class MassAssignmentStub
{
    public function safeCreateTest(Request $request)
    {
        BananaModel::forceCreate($request->only(['somekey']));
    }

    public function safeFillTest(FormRequest $request)
    {
        $banana = new BananaModel();

        $banana->forceFill($request->only(['somekey']));
    }

    public function forceCreateTest(Request $request)
    {
        BananaModel::forceCreate($request->all());
    }

    public function forceFillTest(FormRequest $request)
    {
        $banana = new BananaModel();

        $banana->forceFill($request->all());
    }

    public function builderMassUpdateTest(Request $request)
    {
        BananaModel::query()->update($request->all());
    }

    public function firstOrCreateTest(Request $request)
    {
        BananaModel::firstOrCreate($request->all());
    }

    public function builderTest(Request $request)
    {
        BananaModel::where('someColumn', 1)->update($request->all());
        BananaModel::where('someColumn', 1)->where('anothercolumn', 2)->update($request->all());
        BananaModel::query()->upsert($request->all(), []);
    }

    public function savedRequestDataTest(FormRequest $request)
    {
        $x = $request->all();
        BananaModel::where('somestuff', 1)->update($x);
        (new BananaModel)->forceFill($x)->save();
    }

    public function safeBuilderTest(FormRequest $request)
    {
        BananaModel::where('someColumn', 1)->update($request->validated());
        BananaModel::where('someColumn', 1)->where('anothercolumn', 2)->update($request->only(['somevalues']));
        BananaModel::query()->upsert($request->only(['ok']), []);
    }

    public function safeRequestDataWhitelistTest(Request $request)
    {
        $x = $request->all();
        BananaModel::where('somestuff', 1)->update(Arr::only($x, ['name', 'description']));
        BananaModel::where('somestuff', 1)->update(array_filter($x, function($key) {
            return in_array($key, ['name', 'description']);
        }));
    }
}
