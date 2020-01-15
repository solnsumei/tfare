<?php

namespace App\Http\Controllers;

use App\City;
use App\Route;
use App\Terminal;
use Illuminate\Http\Request;


class ApiController extends Controller
{
    public function cities() {
        $models = City::with('parks')->get();

        return response([
            "message" => "Items fetched successfully",
            "cities" => $models,
        ]);
    }

    public function getParksByCity($id) {
        $model = City::with('parks')->find($id);

        return response([
            "message" => "Items fetched successfully",
            "parks" => $model->parks,
        ]);
    }

    public function searchRoutes(Request $request) {
        $data = $this->validate($request, [
            'from' => 'required|exists:cities,id',
            'to' => 'required|different:from|exists:cities,id',
            'park' => 'exists:parks,id'
        ]);

        $models = Route::with('terminal', 'terminal.company', 'terminal.park')->where([
            'source_id' => $data['from'],
            'destination_id' => $data['to'],
        ]);

        if ($request->has('park')) {
            $models = $models->whereHas('terminal', function($query) use ($data) {
                return $query->where('park_id', $data['park']);
            });
        }

        return response([
            "message" => "Items fetched successfully",
            "routes" => $models->get(),
        ]);
    }

    public function terminal($slug) {
        $model = Terminal::with('routes')
            ->where('slug', $slug)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            "message" => "Item fetched successfully",
            "terminal" => $model,
        ]);
    }

    public function routes($slug) {
        $model = Terminal::with('routes')
            ->where('slug', $slug)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            "message" => "Items fetched successfully",
            "routes" => $model->routes,
        ]);
    }

    public function route($slug, $routeId) {
        $model = Route::with('terminal')
            ->where('id', $routeId)
            ->whereHas('terminal', function ($query) use ($slug) {
                return $query->where('slug', $slug);
            })->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            "message" => "Item fetched successfully",
            "route" => $model,
        ]);
    }
}
