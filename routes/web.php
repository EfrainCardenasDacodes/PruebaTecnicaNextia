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

$router->group(['prefix'=>'api/v1'], function () use ($router) {
    //Auth
    $router->post('/login', 'UserController@authenticate');
    $router->post('/signup', 'UserController@createUser');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('/me', 'UserController@me');
    });

    $router->group(['middleware' => 'auth','prefix'=>'bien'], function () use ($router) {
        $router->get('/list', 'BienController@listBiens');
        $router->post('/create', 'BienController@createBien');
        $router->get('/view/{id}', 'BienController@viewBien');
        $router->put('/update/{id}', 'BienController@updateBien');
        $router->delete('/delete/{id}', 'BienController@deleteBien');
        $router->get('/viewmany', 'BienController@viewManyBien');

        $router->get('/seed', 'BienController@seedBienes');
    });
});
