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
$router->group(['prefix' => 'admin/totalcash'], function () use ($router) {
    $router->get('/',['uses'=>'TotalCash\TotalCashController@index']);
    $router->post('/search',['uses'=>'TotalCash\TotalCashController@Search']);
    $router->post('/store',['uses'=>'TotalCash\TotalCashController@store']);
    $router->put('/update/{id}',['uses'=>'TotalCash\TotalCashController@update']);
    $router->delete('/delete/{id}',['uses'=>'TotalCash\TotalCashController@destroy']);
});
$router->group(['prefix' => 'users/totalcash'], function () use ($router) {
    $router->get('/',['uses'=>'TotalCash\TotalCashController@index']);
});