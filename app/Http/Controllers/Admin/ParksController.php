<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use App\Park;
use Illuminate\Support\Str;

class ParksController extends Controller
{
    public function index(Request $request) {
        $cityId = $request->query('cityId');

        $models = $cityId
            ? Park::with('city')->where('city_id', $cityId)->orderBy('name', 'asc')->get()
            : Park::with('city')->get();

        return response([
            'message' => 'Items fetched successfully',
            'parks' => $models,
        ]);
    }

    public function store(Request $request) {
        $data = $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'city_id' => 'required|exists:cities,id'
        ]);

        $nameTitleCase = Str::title($data['name']);

        if(Park::where(['name' => $nameTitleCase, 'city_id' => $data['city_id']])->first() != null) {
            return Helper::duplicateResponse();
        };

        $slug = $this->makeSlug($nameTitleCase);

        $model = Park::create([
            'name' => $nameTitleCase,
            'slug' => $slug,
            'city_id' => $data['city_id'],
        ]);

        if ($model) {
            return response([
                'message' => 'Item added successfully',
                'park' => $model
            ], 201);
        }

        return Helper::notSavedResponse();
    }

    public function show($id) {
        $model = Park::with('city')->where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'Item fetched successfully',
            'park' => $model,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $this->validate($request, [
            'name' => 'min:3|max:30',
            'city_id' => 'exists:cities,id'
        ]);

        $model = Park::with('city')->where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        $nameTitleCase = $request->has('name') ? Str::title($data['name']) : $model->name;
        $cityId = $request->has('city_id') ? $data['city_id'] : $model->city_id;

        $duplicatePark = Park::where(['name' => $nameTitleCase, 'city_id' => $cityId])->first();

        if ($duplicatePark && $duplicatePark->id != $model->id) {
            return Helper::duplicateResponse();
        }

        $model->slug = $nameTitleCase != $model->name ? $this->makeSlug($nameTitleCase) : $model->slug;
        $model->name = $nameTitleCase;


        $model->city_id = $cityId;

        if ($model->save()) {
            return response([
                'message' => 'Item updated successfully',
                'park' => $model,
            ]);
        }

        return Helper::notSavedResponse();
    }

    public function delete($id) {
        $model = Park::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        if ($model->delete()) {
            return response([
                'message' => 'Item was deleted successfully',
            ]);
        }

        return Helper::deleteFailureResponse();
    }

    private function makeSlug($title)
    {
        $slug = Str::slug($title, '-');

        $count = Park::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
