<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use App\Terminal;
use Illuminate\Support\Str;

class TerminalsController extends Controller
{
    public function index() {
        $models = Terminal::all();

        return response([
            'message' => 'Items fetched successfully',
            'terminals' => $models,
        ]);
    }

    public function store(Request $request) {
        $data = $this->validate($request, [
            'name' => 'required|min:3|max:30',
            'phone' => 'digits_between:11, 15',
            'address' => 'min:3|max:150',
            'company_id' => 'required|exists:companies,id',
            'park_id' => 'required|exists:parks,id',
            'city_id' => 'required|exists:cities,id',
        ]);

        $nameTitleCase = Str::title($data['name']);

        if(Terminal::where([
                'name' => $nameTitleCase,
                'park_id' => $data['park_id'],
                'company_id' => $data['company_id']
            ])->first() != null)
        {
            return Helper::duplicateResponse();
        };

        $data['slug'] = $this->makeSlug($nameTitleCase);
        $data['name'] = $nameTitleCase;

        $model = Terminal::create($data);

        if ($model) {
            return response([
                'message' => 'Item added successfully',
                'terminal' => $model
            ], 201);
        }

        return Helper::notSavedResponse();
    }

    public function show($id) {
        $model = Terminal::where('id', $id)->with('routes')->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'Item fetched successfully',
            'terminal' => $model,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $this->validate($request, [
            'name' => 'min:3|max:30',
            'phone' => 'digits_between:11, 15',
            'address' => 'min:3|max:150',
            'company_id' => 'exists:companies,id',
            'park_id' => 'exists:parks,id',
            'city_id' => 'exists:cities,id',
        ]);

        $model = Terminal::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        $nameTitleCase = $request->has('name') ? Str::title($data['name']) : $model->name;

        $parkId = $request->has('park_id') ? $data['park_id'] : $model->park_id;
        $companyId = $request->has('company_id') ? $data['company_id'] : $model->company_id;

        $duplicateTerminal = Terminal::where([
            'name' => $nameTitleCase, 'company_id' => $companyId, 'park_id' => $parkId,
        ])->first();

        if ($duplicateTerminal && $duplicateTerminal->id != $model->id) {
            return Helper::duplicateResponse();
        }

        $model->slug = $nameTitleCase != $model->name ? $this->makeSlug($nameTitleCase) : $model->slug;
        $model->name = $nameTitleCase;

        $model->phone = $request->has('phone') ? $data['phone'] : $model->phone;
        $model->address = $request->has('address') ? $data['address'] : $model->address;
        $model->city_id = $request->has('city_id') ? $data['city_id'] : $model->city_id;

        $model->park_id = $parkId;
        $model->company_id = $companyId;

        if ($model->save()) {
            return response([
                'message' => 'Item updated successfully',
                'terminal' => $model,
            ]);
        }

        return Helper::notSavedResponse();
    }

    public function delete($id) {
        $model = Terminal::where('id', $id)->first();

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

        $count = Terminal::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
