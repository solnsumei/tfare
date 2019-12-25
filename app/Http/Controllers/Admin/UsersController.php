<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use App\User;

class UsersController extends Controller
{
    public function index() {
        $models = User::with('role')->orderBy('name', 'asc')->get();

        return response([
            'message' => 'Users fetched successfully',
            'users' => $models,
        ]);
    }

    public function show($id) {
        $model = User::with('role')->where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'User fetched successfully',
            'user' => $model,
        ]);
    }
}
