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
$router->group(['prefix' => 'admin/results','middleware'=>'admin'], function () use ($router) {
    $router->get('/',['uses'=>'Results\ResultsController@index']);
    $router->post('/store',['uses'=>'Results\ResultsController@store']);
    $router->put('/update/{id}',['uses'=>'Results\ResultsController@update']);
    $router->delete('/destroy/{id}',['uses'=>'Results\ResultsController@destroy']);
});
$router->group(['prefix' => 'admin/Results'], function () use ($router) {
    $router->get('/',['uses'=>'Results\ResultsController@index']);
});
