<?php

namespace App\Http\Controllers;

class Helper extends Controller
{
    public static function notFoundResponse() {
        return response([
            'message' => 'Resource not found',
        ], 404);
    }

    public static function notSavedResponse() {
        return response([
            'message' => 'Item could not be saved',
        ], 409);
    }

    public static function deleteSuccessResponse() {
        return response([
            'message' => 'Item was deleted successfully',
        ]);
    }

    public static function deleteFailureResponse() {
        return response([
            'message' => 'Item could not be deleted',
        ], 409);
    }

    public static function duplicateResponse() {
        return response([
            'message' => 'Item with same credentials already exists',
        ], 409);
    }

    public static function sameCityResponse() {
        return response([
            'errors' => [
                'terminal' => ['Terminal and source must be from same city']
            ]
        ], 400);
    }
}
