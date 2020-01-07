<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->post('login', 'AuthController@login');
    $router->get('cities/{id}/parks', 'ApiController@getParksByCity');
    $router->get('cities', 'ApiController@cities');


    $router->get('search', 'ApiController@searchRoutes');
    $router->get('terminals/{slug}/routes/{routeId}', 'ApiController@route');
    $router->get('terminals/{slug}/routes', 'ApiController@routes');
    $router->get('terminals/{slug}', 'ApiController@terminal');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('logout', 'AuthController@logout');

        $router->group(['prefix' => 'admin', 'middleware' => 'superAdmin'], function () use ($router) {

            $router->group(['prefix' => 'users'], function () use ($router) {
                $router->get('{id}', 'Admin\UsersController@show');
                $router->get('/', 'Admin\UsersController@index');
            });

            $router->group(['prefix' => 'cities'], function () use ($router) {
                $router->get('{id}', 'Admin\CitiesController@show');
                $router->put('{id}', 'Admin\CitiesController@update');
                $router->delete('{id}', 'Admin\CitiesController@delete');
                $router->get('/', 'Admin\CitiesController@index');
                $router->post('/', 'Admin\CitiesController@store');
            });

            $router->group(['prefix' => 'parks'], function () use ($router) {
                $router->get('{id}', 'Admin\ParksController@show');
                $router->put('{id}', 'Admin\ParksController@update');
                $router->delete('{id}', 'Admin\ParksController@delete');
                $router->get('/', 'Admin\ParksController@index');
                $router->post('/', 'Admin\ParksController@store');
            });

            $router->group(['prefix' => 'terminals'], function () use ($router) {
                $router->get('{id}', 'Admin\TerminalsController@show');
                $router->put('{id}', 'Admin\TerminalsController@update');
                $router->delete('{id}', 'Admin\TerminalsController@delete');
                $router->get('/', 'Admin\TerminalsController@index');
                $router->post('/', 'Admin\TerminalsController@store');
            });

            $router->group(['prefix' => 'companies'], function () use ($router) {
                $router->get('{id}', 'Admin\CompaniesController@show');
                $router->put('{id}', 'Admin\CompaniesController@update');
                $router->delete('{id}', 'Admin\CompaniesController@delete');
                $router->get('/', 'Admin\CompaniesController@index');
                $router->post('/', 'Admin\CompaniesController@store');
            });

            $router->group(['prefix' => 'routes'], function () use ($router) {
                $router->get('{id}', 'Admin\RoutesController@show');
                $router->put('{id}', 'Admin\RoutesController@update');
                $router->delete('{id}', 'Admin\RoutesController@delete');
                $router->get('/', 'Admin\RoutesController@index');
                $router->post('/', 'Admin\RoutesController@store');
            });
        });
    });

    $router->get('/', function () {
        return response([
            'message' => 'Welcome to Tfare App!'
        ]);
    });
});

$router->get('/{route:.*}/', function () use ($router) {
    return view('index');
});
