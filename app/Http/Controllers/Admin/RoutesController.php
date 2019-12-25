<?php

namespace App\Http\Controllers\Admin;

use App\Terminal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper;
use App\Route;

class RoutesController extends Controller
{
    public function index() {
        $models = Route::all();

        return response([
            'message' => 'Items fetched successfully',
            'routes' => $models,
        ]);
    }

    public function store(Request $request) {
        $data = $this->validate($request, [
            'source' => 'required|exists:cities,id',
            'destination' => 'required|different:source|exists:cities,id',
            'terminal' => 'required|exists:terminals,id',
            'fares' => 'required|array|min:1|max:10',
            'fares.*.vehicle' => 'required|string|distinct|min:2|max:30',
            'fares.*.noOfSeats' => 'required|integer|min:1',
            'fares.*.price' => 'required|numeric|min:1',
            'fares.*.onlineBookingPrice' => 'required|numeric|min:1',
        ]);


        if(Route::where([
                'source_id' => $data['source'],
                'destination_id' => $data['destination'],
                'terminal_id' => $data['terminal']
            ])->first() != null) {
            return Helper::duplicateResponse();
        };

        $terminal = Terminal::find($data['terminal']);

        if ($terminal->city_id != $data['source']) {
            return Helper::sameCityResponse();
        }

        $model = Route::create([
            'source_id' => $data['source'],
            'destination_id' => $data['destination'],
            'terminal_id' => $data['terminal'],
            'fares' => $data['fares'],
        ]);

        if ($model) {
            return response([
                'message' => 'Item added successfully',
                'route' => $model
            ], 201);
        }

        return Helper::notSavedResponse();
    }

    public function show($id) {
        $model = Route::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        return response([
            'message' => 'Item fetched successfully',
            'route' => $model,
        ]);
    }

    public function update(Request $request, $id) {
        $data = $this->validate($request, [
            'source' => 'exists:cities,id',
            'destination' => 'different:source|exists:cities,id',
            'terminal' => 'exists:terminals,id',
            'fares' => 'array|min:1|max:10',
            'fares.*.vehicle' => 'required_with:fares|string|distinct|min:2|max:30',
            'fares.*.noOfSeats' => 'required_with:fares|integer|min:1',
            'fares.*.price' => 'required_with:fares|numeric|min:1',
            'fares.*.onlineBookingPrice' => 'required_with:fares|numeric|min:1',
        ]);

        $model = Route::where('id', $id)->first();

        if (!$model) {
            return Helper::notFoundResponse();
        }

        $source = $request->has('source') ? $data['source'] : $model->source_id;
        $destination = $request->has('destination') ? $data['destination'] : $model->destination_id;
        $terminalId = $request->has('terminal') ? $data['terminal'] : $model->terminal_id;


        $duplicateRoute = Route::where([
            'source_id' => $source,
            'destination_id' => $destination,
            'terminal_id' => $terminalId
        ])->first();

        if ($duplicateRoute && $duplicateRoute->id != $model->id) {
            return Helper::duplicateResponse();
        }

        $terminal = Terminal::find($terminalId);

        if ($terminal->city_id != $data['source']) {
            return Helper::sameCityResponse();
        }

        if ($request->has('fares')) {
            $model->fares = $data['fares'];
        }

        $model->source_id = $source;
        $model->destination_id = $destination;
        $model->terminal_id = $terminalId;

        if ($model->save()) {
            return response([
                'message' => 'Item updated successfully',
                'route' => $model,
            ]);
        }

        return Helper::notSavedResponse();
    }

    public function delete($id) {
        $model = Route::where('id', $id)->first();

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
