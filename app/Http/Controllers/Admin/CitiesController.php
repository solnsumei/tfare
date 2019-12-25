<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Support\Str;
use App\City;

class CitiesController extends Controller
{
    public function index() {
        $models = City::orderBy('name', 'asc')->get();

        return response([
            'message' => 'Items fetched successfully',
            'cities' => $models,
        ]);
    }

    public function store(Request $request) {
        $data = $this->validate($request, [
            'name' => 'required|min:2|max:30',
            'country' => 'required|min:3|max:30'
        ]);

        $nameTitleCase = Str::title($data['name']);
        $countryTitleCase = Str::title($data['country']);

        if(City::where(['name' => $nameTitleCase, 'country' => $countryTitleCase])->first() != null) {
            return Helper::duplicateResponse();
        };

        $model = City::create([
            'name' => $nameTitleCase,
            'country' => $countryTitleCase,
        ]);

        if ($model) {
            return response([
                'message' => 'Item added successfully',
                'city' => $model
            ], 201);
        }

        return Helper::notSavedResponse();
    }

    public function show($id) {
        $model = City::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'Item fetched successfully',
            'city' => $model,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $this->validate($request, [
            'name' => 'min:2|max:30',
            'country' => 'min:3|max:30'
        ]);

        $model = City::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        $nameTitleCase = $request->has('name') ? Str::title($data['name']) : $model->name;
        $countryTitleCase = $request->has('country') ? Str::title($data['country']) : $model->country;

        $duplicateCity = City::where(['name' => $nameTitleCase, 'country' => $countryTitleCase])->first();

        if ($duplicateCity && $duplicateCity->id != $model->id) {
            return Helper::duplicateResponse();
        }

        $model->name = $nameTitleCase;
        $model->country = $countryTitleCase;

        if ($model->save()) {
            return response([
                'message' => 'Item updated successfully',
                'city' => $model,
            ]);
        }

        return Helper::notSavedResponse();
    }

    public function delete($id) {
        $model = City::where('id', $id)->first();

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
}
