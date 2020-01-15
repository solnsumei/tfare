<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use Illuminate\Support\Str;
use App\Company;

class CompaniesController extends Controller
{

    public function index() {
        $models = Company::withCount('terminals')->orderBy('name', 'asc')->get();

        return response([
            'message' => 'Items fetched successfully',
            'companies' => $models,
        ]);
    }

    public function store(Request $request) {
        $data = $this->validate($request, [
            'name' => 'required|min:2|max:30|unique:companies',
            'logoUrl' => 'required|url|max:150'
        ]);

        $nameTitleCase = Str::title($data['name']);
        $slug = $this->makeSlug($nameTitleCase);

        $model = Company::create([
            'name' => $nameTitleCase,
            'slug' => $slug,
            'logoUrl' => $data['logoUrl'],
        ]);

        if ($model) {
            return response([
                'message' => 'Item added successfully',
                'company' => $model
            ], 201);
        }

        return Helper::notSavedResponse();
    }

    public function show($id) {
        $model = Company::with('terminals')->where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'Item fetched successfully',
            'company' => $model,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $this->validate($request, [
            'name' => 'min:2|max:30|unique:companies,name,'.$id,
            'logoUrl' => 'min:3|max:150',
        ]);

        $model = Company::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        $nameTitleCase = $request->has('name') ? Str::title($data['name']) : $model->name;

        $model->logoUrl = $request->has('logoUrl') ? $data['logoUrl'] : $model->logoUrl;
        $model->slug = $nameTitleCase != $model->name ? $this->makeSlug($nameTitleCase) : $model->slug;
        $model->name = $nameTitleCase;


        if ($model->save()) {
            return response([
                'message' => 'Item updated successfully',
                'company' => $model,
            ]);
        }

        return Helper::notSavedResponse();
    }

    public function delete($id) {
        $model = Company::where('id', $id)->first();

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

        $count = Company::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
