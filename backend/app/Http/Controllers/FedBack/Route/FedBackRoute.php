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
$router->group(['prefix' => 'admin/category'], function () use ($router) {
    $router->post('/store',['uses'=>'FedBack\FedBackController@store']);
    // $router->post('/update/{id}',['uses'=>'FedBack\FedBackController@update']);
    // $router->delete('/destroy/{id}',['uses'=>'FedBack\FedBackController@destroy']);
});