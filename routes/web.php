<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/iuran'], function () use ($router) {
    $router->get('/', 'IuransController@index');
    $router->get('/{id:[\d]+}', ['as' => 'peminjaman.show', 'uses' => 'IuransController@show']);
    $router->post('/', 'IuransController@store');
    $router->put('/{id:[\d]+}', 'IuransController@update');
    $router->delete('/{id:[\d]+}', 'IuransController@destroy');
    $router->get('/tunggakan/{tahun:[\d]+}', 'IuransController@getTunggakan');
});