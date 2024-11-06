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
$router->group(['prefix' => 'admin/bank'], function () use ($router) {
    $router->get('/',['uses'=>'Bank\BankController@index']);
    $router->post('/search',['uses'=>'Bank\BankController@Search']);
    $router->post('/store',['uses'=>'Bank\BankController@store']);
    $router->put('/update/{id}',['uses'=>'Bank\BankController@update']);
    $router->delete('/delete/{id}',['uses'=>'Bank\BankController@destroy']);
});
$router->group(['prefix' => 'users/bank'], function () use ($router) {
    $router->get('/',['uses'=>'Bank\BankController@index']);
});